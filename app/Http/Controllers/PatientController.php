<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{

    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nom_complet', 'like', "%$search%")
                ->orWhere('telephone', 'like', "%$search%");
        }

        $patients = $query->paginate(10);

        return view('patients.index', compact('patients'));
    }


    public function create()
    {
        return view('patients.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet'   => 'required|string|min:3|max:100|regex:/^[\pL\s\-\'’]+$/u',
            'age'           => 'nullable|integer|min:0|max:150',
            'num_telephone' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'maladies'      => 'nullable|string',
            'notes'         => 'nullable|string',
        ], [
            'nom_complet.required' => 'Le nom complet est obligatoire.',
            'nom_complet.min'      => 'Le nom doit contenir au moins 3 caractères.',
            'nom_complet.max'      => 'Le nom ne peut pas dépasser 100 caractères.',
            'nom_complet.regex'    => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',

            'num_telephone.regex'  => 'Le numéro de téléphone contient des caractères invalides.',
            'num_telephone.max'    => 'Le numéro de téléphone est trop long.',

            'age.integer'          => 'L\'âge doit être un nombre entier.',
            'age.min'              => 'L\'âge ne peut pas être négatif.',
            'age.max'              => 'L\'âge ne peut pas dépasser 150 ans.',
        ]);

        Patient::create($validated);
        return redirect()->route('patients.index')
            ->with('success', 'Patient ajouté avec succès.');
    }


    public function show($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Patient introuvable');
        }
        $patient = Patient::findOrFail($id);

        $rendezVous = $patient->rendezvous()
            ->with('dentiste')
            ->orderBy('date_heure', 'desc')
            ->get();

        $stats = [
            'prochain_rdv' => $rendezVous->where('date_heure', '>', now())->where('statut', 'prevu')->first(),
        ];

        return view('patients.show', compact('patient', 'rendezVous', 'stats'));
    }

    public function edit($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Patient introuvable');
        }
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }


    public function update(Request $request, $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Patient introuvable');
        }
        $validated = $request->validate([
            'nom_complet'   => 'required|string|min:3|max:100|regex:/^[\pL\s\-\'’]+$/u',
            'age'           => 'nullable|integer|min:0|max:150',
            'num_telephone' => 'nullable|string|max:15|regex:/^[0-9+\-\s]+$/',
            'maladies'      => 'nullable|string',
            'notes'         => 'nullable|string',
        ], [
            'nom_complet.required' => 'Le nom complet est obligatoire.',
            'nom_complet.min'      => 'Le nom doit contenir au moins 3 caractères.',
            'nom_complet.max'      => 'Le nom ne peut pas dépasser 100 caractères.',
            'nom_complet.regex'    => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',

            'num_telephone.regex'  => 'Le numéro de téléphone contient des caractères invalides.',
            'num_telephone.max'    => 'Le numéro de téléphone est trop long.',

            'age.integer'          => 'L\'âge doit être un nombre entier.',
            'age.min'              => 'L\'âge ne peut pas être négatif.',
            'age.max'              => 'L\'âge ne peut pas dépasser 150 ans.',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient modifié avec succès.');
    }

    public function destroy($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Patient introuvable');
        }

        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Patient supprimé avec succès.');
    }
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $patients = Patient::where('nom_complet', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nom_complet']);

        return response()->json($patients);
    }
}
