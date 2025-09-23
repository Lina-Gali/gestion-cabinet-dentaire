<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Dentist;

class RendezVousController extends Controller
{

    public function index()
    {
        //$today = Carbon::today();

        $rendezVous = RendezVous::with(['patient', 'dentiste'])
            ->orderBy('date_heure', 'asc')
            ->paginate(10);

        return view('rendezvous.index', compact('rendezVous'));
    }


    public function create()
    {
        $patients = Patient::all();
        $dentistes = Dentist::all();
        return view('rendezvous.create', compact('patients', 'dentistes'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentiste_id' => 'required|exists:dentists,id',
            'date_heure' => 'required|date',
            'motif' => 'required|in:consultation,extraction,soins_dentaires,prothese',
        ]);

        RendezVous::create($request->all());

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous ajouté avec succès.');
    }


    public function edit($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $patients = Patient::all();

        return view('rendezvous.edit', compact('rendezVous', 'patients'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentiste_id' => 'required|exists:dentists,id',
            'date_heure' => 'required|date',
            'motif' => 'required|in:consultation,extraction,soins_dentaires,prothese',

        ]);

        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update($request->all());

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous modifié avec succès.');
    }
}
