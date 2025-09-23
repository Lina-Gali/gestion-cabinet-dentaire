@extends('layouts.app')

@section('title', 'Liste des Rendez-Vous')

@section('content')
<div class="page-header">
    <h1 class="page-title">Rendez-Vous du Jour</h1>
    <p class="page-subtitle">Liste de tous les rendez-vous prévus aujourd’hui</p>
</div>

<div class="card">
    <div class="card-header">
        <form class="search-filters">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Rechercher un patient...">
            </div>
        </form>
        <a href="{{ route('rendezvous.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Rendez-Vous
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Dentiste</th>
                    <th>Date_heure</th>
                    <th>Motif</th>

                </tr>
            </thead>
            <tbody>
                @forelse($rendezVous as $rdv)
                <tr>
                    <td>{{ $rdv->patient->nom_complet }}</td>
                    <td>{{ $rdv->dentiste->nom_dentiste?? '—' }}</td>
                    <td>
                        {{ $rdv->date_heure->format('d/m/Y') }}
                        <span class="text-muted" style="font-size: 0.95em;">{{ $rdv->date_heure->format('H:i') }}</span>
                    </td>
                    <td>{{ ucfirst($rdv->motif) }}</td>

                </tr>
                @empty
                <tr>
                    <td colspan="5">Aucun rendez-vous prévu aujourd’hui.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $rendezVous->links() }}
    </div>
</div>
@endsection