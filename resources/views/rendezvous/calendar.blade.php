@extends('layouts.app')

@section('title', 'Calendrier des Rendez-Vous')

@push('styles')
<style>
    .calendar-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .calendar-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .week-navigation {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .week-nav-btn {
        padding: 8px 12px;
        background: #f1f5f9;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s;
    }

    .week-nav-btn:hover {
        background: #e2e8f0;
        color: #334155;
    }

    .week-title {
        font-weight: 600;
        color: #1e293b;
        margin: 0 8px;
    }

    .calendar-filters {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .calendar-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .calendar-table th {
        background: #f8fafc;
        padding: 12px 8px;
        font-weight: 600;
        color: #374151;
        font-size: 14px;
        border-bottom: 2px solid #e2e8f0;
        text-align: center;
    }

    .calendar-table .time-column {
        width: 80px;
        background: #f8fafc;
        font-weight: 500;
        color: #64748b;
        text-align: center;
        border-right: 2px solid #e2e8f0;
    }

    .calendar-table td {
        border: 1px solid #e2e8f0;
        vertical-align: top;
        height: 50px;
        position: relative;
    }

    .calendar-table .time-cell {
        padding: 8px 4px;
        font-size: 12px;
        background: #fafafa;
    }

    .day-header {
        text-align: center;
        padding: 8px;
    }

    .day-name {
        font-weight: 600;
        color: #1e293b;
        display: block;
    }

    .day-date {
        font-size: 12px;
        color: #64748b;
        display: block;
        margin-top: 2px;
    }

    .day-header.today {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 8px;
        margin: 4px;
    }

    .day-header.today .day-date {
        color: rgba(255, 255, 255, 0.8);
    }

    .appointment-slot {
        padding: 2px;
        height: 100%;
        min-height: 48px;
    }

    .appointment-item {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 6px;
        padding: 6px 8px;
        margin-bottom: 2px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        overflow: hidden;
    }

    .appointment-item:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
    }

    .appointment-patient {
        font-weight: 600;
        margin-bottom: 2px;
        line-height: 1.2;
    }

    .appointment-motif {
        font-size: 10px;
        opacity: 0.9;
        line-height: 1.2;
    }

    .appointment-dentiste {
        font-size: 10px;
        opacity: 0.8;
        margin-top: 2px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        padding-top: 2px;
    }

    /* Couleurs par motif */
    .appointment-item.consultation {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .appointment-item.extraction {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .appointment-item.soins_dentaires {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }

    .appointment-item.prothese {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .empty-slot {
        color: #94a3b8;
        text-align: center;
        padding-top: 12px;
        font-size: 11px;
        height: 100%;
    }

    /* Actions sur les RDV */
    .appointment-actions {
        position: absolute;
        top: 2px;
        right: 2px;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .appointment-item:hover .appointment-actions {
        opacity: 1;
    }

    .appointment-actions button {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 2px 4px;
        border-radius: 3px;
        cursor: pointer;
        font-size: 10px;
        margin-left: 2px;
    }

    .appointment-actions button:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .calendar-table {
            font-size: 10px;
        }

        .appointment-item {
            padding: 4px 6px;
            font-size: 10px;
        }

        .appointment-patient {
            font-size: 10px;
        }

        .appointment-motif,
        .appointment-dentiste {
            font-size: 9px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Calendrier des Rendez-Vous</h1>
    <p class="page-subtitle">Vue hebdomadaire du planning</p>
</div>

<div class="calendar-container">
    <div class="calendar-header">
        <!-- Navigation semaine -->
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
            <!-- Filtre par dentiste -->
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

    <!-- Calendrier -->
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

<!-- Légende -->
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
