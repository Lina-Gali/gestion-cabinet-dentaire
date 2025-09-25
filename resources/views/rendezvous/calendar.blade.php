@extends('layouts.app')

@section('title', 'Calendrier des Rendez-Vous')

@push('styles')
<link href="{{ asset('css/rdv.css') }}" rel="stylesheet">
@endpush


@section('content')
<div class="page-header">
    <h1 class="page-title">Calendrier des Rendez-Vous</h1>
    <p class="page-subtitle">Vue hebdomadaire du planning</p>
</div>

<div class="calendar-container">
    <div class="calendar-header">
        <div class="week-navigation">
            <a href="{{ route('rendezvous.calendar') }}?week={{ $prevWeek->format('Y-m-d') }}&dentiste_id={{ request('dentiste_id') }}"
                class="week-nav-btn">
                <i class="fas fa-chevron-left"></i>
            </a>
            <h3 class="week-title">
                {{ $weekStart->format('d M') }} - {{ $weekEnd->format('d M Y') }}
            </h3>
            <a href="{{ route('rendezvous.calendar') }}?week={{ $nextWeek->format('Y-m-d') }}&dentiste_id={{ request('dentiste_id') }}"
                class="week-nav-btn">
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <div class="calendar-filters">
            <form method="GET" action="{{ route('rendezvous.calendar') }}" style="display: flex; gap: 12px; align-items: center;">
                <input type="hidden" name="week" value="{{ request('week', $weekStart->format('Y-m-d')) }}">
                <select name="dentiste_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Tous les dentistes</option>
                    @foreach($dentistes as $dentiste)
                    <option value="{{ $dentiste->id }}"
                        {{ request('dentiste_id') == $dentiste->id ? 'selected' : '' }}>
                        {{ $dentiste->nom_dentiste }}
                    </option>
                    @endforeach
                </select>
            </form>

            <a href="{{ route('rendezvous.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau RDV
            </a>
        </div>
    </div>

    <div style="overflow-x: auto;">
        <table class="calendar-table">
            <thead>
                <tr>
                    <th class="time-column">Heure</th>
                    @foreach($calendar as $dayData)
                    <th class="day-header {{ $dayData['date']->isToday() ? 'today' : '' }}">
                        <span class="day-name">
                            @php
                            $dayNames = [
                            'Monday' => 'Lundi',
                            'Tuesday' => 'Mardi',
                            'Wednesday' => 'Mercredi',
                            'Thursday' => 'Jeudi',
                            'Friday' => 'Vendredi',
                            'Saturday' => 'Samedi',
                            'Sunday' => 'Dimanche'
                            ];
                            @endphp
                            {{ $dayNames[$dayData['date']->format('l')] }}
                        </span>
                        <span class="day-date">{{ $dayData['date']->format('d/m') }}</span>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($timeSlots as $timeSlot)
                <tr>
                    <td class="time-column time-cell">{{ $timeSlot }}</td>
                    @foreach($calendar as $dayData)
                    <td class="appointment-slot">
                        @if(count($dayData['appointments'][$timeSlot]) > 0)
                        @foreach($dayData['appointments'][$timeSlot] as $rdv)
                        <div class="appointment-item {{ $rdv->motif }}">
                            <div class="appointment-actions">
                                <a href="{{ route('rendezvous.edit', $rdv->id) }}"
                                    style="color: white; text-decoration: none;">
                                    <button title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </a>
                                <form action="{{ route('rendezvous.destroy', $rdv->id) }}"
                                    method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Supprimer ce RDV ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="appointment-patient">
                                {{ $rdv->patient->nom_complet }}
                            </div>
                            <div class="appointment-motif">
                                {{ ucfirst(str_replace('_', ' ', $rdv->motif)) }}
                            </div>
                            @if($rdv->dentiste)
                            <div class="appointment-dentiste">
                                Dr. {{ $rdv->dentiste->nom_dentiste }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h4>Légende des couleurs</h4>
    </div>
    <div style="padding: 20px; display: flex; gap: 20px; flex-wrap: wrap;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 16px; height: 16px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 4px;"></div>
            <span>Consultation</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 16px; height: 16px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 4px;"></div>
            <span>Extraction</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 16px; height: 16px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); border-radius: 4px;"></div>
            <span>Soins dentaires</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div style="width: 16px; height: 16px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 4px;"></div>
            <span>Prothèse</span>
        </div>
    </div>
</div>
@endsection
