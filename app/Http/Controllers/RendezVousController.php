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
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentiste_id' => 'required|exists:dentists,id',
            'date_heure' => 'required|date',
            'motif' => 'required|in:consultation,extraction,soins_dentaires,prothese',
            'statut' => 'nullable|in:prevu,termine',
        ]);

        RendezVous::create($request->all());

        return redirect()->route('rendezvous.index')
            ->with('success', 'Rendez-vous ajouté avec succès.');
    }

    public function edit($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $patients = Patient::all();
        $dentistes = Dentist::all();

        return view('rendezvous.edit', compact('rendezVous', 'patients', 'dentistes'));
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

    public function destroy($id)
    {
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

        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update(['statut' => $request->statut]);

        return redirect()->route('rendezvous.index')
            ->with('success', 'Statut mis à jour avec succès.');
    }
}
