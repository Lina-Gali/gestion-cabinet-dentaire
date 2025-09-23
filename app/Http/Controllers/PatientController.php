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
        $request->validate([
            'nom_complet' => 'required|string|max:100',
            'age' => 'nullable|integer',
            'num_telephone' => 'nullable|string|max:15',
            'maladies' => 'nullable|string',
        ]);

        Patient::create($request->all());

        return redirect()->route('patients.index')
            ->with('success', 'Patient ajouté avec succès.');
    }


    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.show', compact('patient'));
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_complet' => 'required|string|max:100',
            'age' => 'nullable|integer',
            'num_telephone' => 'nullable|string|max:15',
            'maladies' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($request->all());

        return redirect()->route('patients.index')
            ->with('success', 'Patient modifié avec succès.');
    }

    public function destroy($id)
    {
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
