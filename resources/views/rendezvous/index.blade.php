@extends('layouts.app')

@section('title', 'Gestion des Rendez-Vous')

@push('styles')
<link href="{{ asset('css/rdv.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Gestion des Rendez-Vous</h1>
    <p class="page-subtitle">Organisez et suivez tous vos rendez-vous</p>
</div>

<div class="card">
    <div class="card-header">
        <div class="filters-row">
            <div class="filter-tabs">
                <a href="{{ route('rendezvous.index') }}?filter=all"
                    class="filter-tab {{ request('filter', 'all') === 'all' ? 'active' : '' }}">
                    Tout
                </a>
                <a href="{{ route('rendezvous.index') }}?filter=today"
                    class="filter-tab {{ request('filter', 'today') === 'today' ? 'active' : '' }}">
                    Aujourd'hui
                </a>
                <a href="{{ route('rendezvous.index') }}?filter=week"
                    class="filter-tab {{ request('filter') === 'week' ? 'active' : '' }}">
                    Cette semaine
                </a>
                <a href="{{ route('rendezvous.index') }}?filter=month"
                    class="filter-tab {{ request('filter') === 'month' ? 'active' : '' }}">
                    Ce mois
                </a>
            </div>


        </div>

        <form class="search-filters" method="GET">
            <input type="hidden" name="filter" value="{{ request('filter', 'today') }}">
            <input type="hidden" name="date" value="{{ request('date') }}">

            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text"
                    name="search"
                    class="search-input"
                    placeholder="Rechercher un patient..."
                    value="{{ request('search') }}">
            </div>

            <select name="dentiste_id" class="filter-select" onchange="this.form.submit()">
                <option value="">Tous les dentistes</option>
                @foreach($dentistes as $dentiste)
                <option value="{{ $dentiste->id }}"
                    {{ request('dentiste_id') == $dentiste->id ? 'selected' : '' }}>
                    Dr. {{ $dentiste->nom_dentiste }}
                </option>
                @endforeach
            </select>

            <select name="statut" class="filter-select" onchange="this.form.submit()">
                <option value="">Tous les statuts</option>
                <option value="prevu" {{ request('statut') == 'prevu' ? 'selected' : '' }}>
                    Prévus
                </option>
                <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>
                    Terminés
                </option>
            </select>

        </form>

        <div style="display: flex; gap: 8px;">
            <a href="{{ route('rendezvous.calendar') }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-calendar"></i> Vue Calendrier
            </a>
            <a href="{{ route('rendezvous.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau RDV
            </a>
        </div>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Dentiste</th>
                    <th>Date & Heure</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rendezVous as $rdv)
                @php
                $now = \Carbon\Carbon::now();
                $rdvTime = $rdv->date_heure;
                $timeClass = 'future';
                $timeIcon = 'fas fa-clock';

                if ($rdvTime->isPast()) {
                $timeClass = 'past';
                $timeIcon = 'fas fa-history';
                } elseif ($rdvTime->diffInMinutes($now, false) > -30 && $rdvTime->diffInMinutes($now, false) < 30) {
                    $timeClass='current' ;
                    $timeIcon='fas fa-play-circle' ;
                    }

                    // Générer les initiales du patient
                    $names=explode(' ', $rdv->patient->nom_complet);
                        $initials = '';
                        foreach($names as $name) {
                            $initials .= strtoupper(substr($name, 0, 1));
                        }
                        $initials = substr($initials, 0, 2);
                    @endphp
                <tr>
                    <td>
                        <div class="patient-info">
                            <div class="patient-avatar">{{ $initials }}</div>
                            <div>
                                <div style="font-weight: 600;">{{ $rdv->patient->nom_complet }}</div>
                                @if($rdv->patient->num_telephone)
                                    <div style="font-size: 12px; color: #64748b;">
                                        {{ $rdv->patient->num_telephone }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($rdv->dentiste)
                            <div style="font-weight: 500;">Dr. {{ $rdv->dentiste->nom_dentiste }}</div>
                        @else
                            <span style="color: #94a3b8;">Non assigné</span>
                        @endif
                    </td>
                    <td>
                        <div class="time-indicator {{ $timeClass }}">
                            <i class="{{ $timeIcon }}"></i>
                            <div>
                                <div>{{ $rdv->date_heure->format('d/m/Y') }}</div>
                                <div style="font-size: 13px;">{{ $rdv->date_heure->format('H:i') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="padding: 4px 8px; background: #f1f5f9; border-radius: 6px; font-size: 12px;">
                            {{ ucfirst(str_replace('_', ' ', $rdv->motif)) }}
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            @if($rdv->statut === 'prevu')
                    <span class="status-badge status-pending">Prévu</span>
                    <form action="{{ route('rendezvous.update-status', $rdv->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="statut" value="termine">
                        <button type="submit"
                            class="btn-icon"
                            style="background: #dcfce7; color: #166534;"
                            title="Marquer comme terminé"
                            onclick="return confirm('Marquer ce RDV comme terminé ?')">
                            <i class="fas fa-check"></i>
                        </button>
                    </form>
                    @else
                    <span class="status-badge status-completed">Terminé</span>
                    <form action="{{ route('rendezvous.update-status', $rdv->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="statut" value="prevu">
                        <button type="submit"
                            class="btn-icon"
                            style="background: #fef3c7; color: #92400e;"
                            title="Remettre en prévu"
                            onclick="return confirm('Remettre ce RDV en prévu ?')">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                    @endif
    </div>
    </td>
    <td class="action-buttons">
        <a href="{{ route('patients.show', $rdv->patient->id) }}"
            class="btn-icon btn-view"
            title="Voir patient">
            <i class="fas fa-user"></i>
        </a>
        <a href="{{ route('rendezvous.edit', $rdv->id) }}"
            class="btn-icon btn-edit"
            title="Modifier">
            <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('rendezvous.destroy', $rdv->id) }}"
            method="POST"
            style="display:inline-block;"
            onsubmit="return confirm('Voulez-vous vraiment supprimer ce rendez-vous ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-icon btn-delete" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
            <i class="fas fa-calendar-times" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
            <div>Aucun rendez-vous trouvé pour cette période</div>
            <a href="{{ route('rendezvous.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                <i class="fas fa-plus"></i> Créer le premier RDV
            </a>
        </td>
    </tr>
    @endforelse
    </tbody>
    </table>
</div>

@if($rendezVous->hasPages())
<div class="pagination">
    {{ $rendezVous->appends(request()->query())->links() }}
</div>
@endif
</div>

<div style="position: fixed; bottom: 24px; right: 24px; display: flex; flex-direction: column; gap: 8px;">
    <a href="{{ route('rendezvous.calendar') }}"
        class="btn btn-primary"
        style="border-radius: 50%; width: 56px; height: 56px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);"
        title="Vue calendrier">
        <i class="fas fa-calendar-alt"></i>
    </a>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }
    });
</script>
@endpush