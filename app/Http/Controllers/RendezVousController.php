<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Dentist;

class RendezVousController extends Controller
{

    public function index(Request $request)
    {
        $rendezVous = RendezVous::with(['patient', 'dentiste'])
            ->filter($request->all())
            ->orderBy('date_heure', 'asc')
            ->paginate(10);

        $dentistes = Dentist::all();
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));

        return view('rendezvous.index', compact('rendezVous', 'dentistes', 'selectedDate'));
    }
    public function indexHome()
    {
        $today = Carbon::today();

        $rendezVous = RendezVous::with(['patient', 'dentiste'])
            ->whereDate('date_heure', $today)
            ->orderBy('date_heure', 'asc')
            ->paginate(10);

        return view('home.acceuil', compact('rendezVous'));
    }

    public function calendar(Request $request)
    {
        $weekStart = $request->get('week')
            ? Carbon::parse($request->get('week'))->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $query = RendezVous::with(['patient', 'dentiste'])
            ->whereBetween('date_heure', [$weekStart, $weekEnd]);

        if ($request->filled('dentiste_id')) {
            $query->where('dentiste_id', $request->dentiste_id);
        }

        $rendezVous = $query->orderBy('date_heure', 'asc')->get();

        $calendar = [];
        $timeSlots = $this->generateTimeSlots();

        for ($date = $weekStart->copy(); $date->lte($weekEnd); $date->addDay()) {
            $dayKey = $date->format('Y-m-d');
            $calendar[$dayKey] = [
                'date' => $date->copy(),
                'appointments' => []
            ];

            foreach ($timeSlots as $time) {
                $calendar[$dayKey]['appointments'][$time] = [];
            }
        }

        foreach ($rendezVous as $rdv) {
            $dayKey = $rdv->date_heure->format('Y-m-d');
            $timeKey = $rdv->date_heure->format('H:i');

            if (isset($calendar[$dayKey]['appointments'][$timeKey])) {
                $calendar[$dayKey]['appointments'][$timeKey][] = $rdv;
            }
        }

        $dentistes = Dentist::all();

        $prevWeek = $weekStart->copy()->subWeek();
        $nextWeek = $weekStart->copy()->addWeek();

        return view('rendezvous.calendar', compact(
            'calendar',
            'timeSlots',
            'weekStart',
            'weekEnd',
            'dentistes',
            'prevWeek',
            'nextWeek'
        ));
    }

    private function generateTimeSlots()
    {
        $slots = [];
        for ($hour = 9; $hour < 21; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }
        return $slots;
    }

    public function create()
    {
        $patients = Patient::all();
        $dentistes = Dentist::all();
        return view('rendezvous.create', compact('patients', 'dentistes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'dentiste_id' => 'required|exists:dentists,id',
            'date_heure'  => 'required|date|after_or_equal:now',
            'motif'       => 'required|in:consultation,extraction,soins_dentaires,prothese',
            'statut'      => 'nullable|in:prevu,termine',
        ], [
            'patient_id.required'  => 'Veuillez sélectionner un patient.',
            'patient_id.exists'    => 'Le patient sélectionné est invalide.',

            'dentiste_id.required' => 'Veuillez sélectionner un dentiste.',
            'dentiste_id.exists'   => 'Le dentiste sélectionné est invalide.',

            'date_heure.required'  => 'La date et l\'heure du rendez-vous sont obligatoires.',
            'date_heure.date'      => 'La date du rendez-vous n\'est pas valide.',
            'date_heure.after_or_equal' => 'La date du rendez-vous doit être égale ou postérieure à maintenant.',

            'motif.required'       => 'Le motif du rendez-vous est obligatoire.',
            'motif.in'             => 'Le motif sélectionné est invalide.',

            'statut.in'            => 'Le statut doit être soit "prévu" soit "terminé".',
        ]);

        RendezVous::create($validated);

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous ajouté avec succès.');
    }

    public function edit($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Rendez-vous introuvable');
        }

        $rendezVous = RendezVous::findOrFail($id);
        $patients = Patient::all();
        $dentistes = Dentist::all();

        return view('rendezvous.edit', compact('rendezVous', 'patients', 'dentistes'));
    }

    public function update(Request $request, $id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Rendez-vous introuvable');
        }

        $validated = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'dentiste_id' => 'required|exists:dentists,id',
            'date_heure'  => 'required|date|after_or_equal:now',
            'motif'       => 'required|in:consultation,extraction,soins_dentaires,prothese',
            'statut'      => 'nullable|in:prevu,termine',
        ], [
            'patient_id.required'  => 'Veuillez sélectionner un patient.',
            'patient_id.exists'    => 'Le patient sélectionné est invalide.',

            'dentiste_id.required' => 'Veuillez sélectionner un dentiste.',
            'dentiste_id.exists'   => 'Le dentiste sélectionné est invalide.',

            'date_heure.required'  => 'La date et l\'heure du rendez-vous sont obligatoires.',
            'date_heure.date'      => 'La date du rendez-vous n\'est pas valide.',
            'date_heure.after_or_equal' => 'La date du rendez-vous doit être égale ou postérieure à maintenant.',

            'motif.required'       => 'Le motif du rendez-vous est obligatoire.',
            'motif.in'             => 'Le motif sélectionné est invalide.',

            'statut.in'            => 'Le statut doit être soit "prévu" soit "terminé".',
        ]);

        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update($validated);

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous modifié avec succès.');
    }

    public function destroy($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Rendez-vous introuvable');
        }
        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->delete();

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous supprimé avec succès.');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:prevu,termine',
        ]);
        if (!is_numeric($id) || $id <= 0) {
            abort(404, 'Rendez-vous introuvable');
        }

        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update(['statut' => $request->statut]);

        return redirect()->route('rendezvous.index')
            ->with('success', 'Statut mis à jour avec succès.');
    }
}
