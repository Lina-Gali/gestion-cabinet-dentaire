@extends('layouts.app')

@section('title', $patient->nom_complet . ' - Dentina')

@push('styles')
<link href="{{ asset('css/patients.css') }}" rel="stylesheet">
@endpush


@section('content')
<!-- Patient Header -->
<div class="patient-header">
    <div class="patient-info">
        <div class="patient-avatar">
            @php
                $names = explode(' ', $patient->nom_complet);
                $initials = '';
                foreach($names as $name) {
                    $initials .= strtoupper(substr($name, 0, 1));
                }
                echo substr($initials, 0, 2);
            @endphp
        </div>
        <div class="patient-details">
            <h1>{{ $patient->nom_complet }}</h1>
            <div class="patient-id">Patient #{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="patient-meta">
                @if($patient->age)
                    <div class="meta-item">
                        <i class="fas fa-birthday-cake"></i>
                        {{ $patient->age }} ans
                    </div>
                @endif
                @if($patient->num_telephone)
                    <div class="meta-item">
                        <i class="fas fa-phone"></i>
                        {{ $patient->num_telephone }}
                    </div>
                @endif
               
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Left Column -->
    <div>
        <!-- Patient Information -->
        <div class="section-card" style="margin-bottom: 32px;">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-user"></i>
                    Informations Personnelles
                </h2>
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier
                </a>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nom Complet</div>
                    <div class="info-value">{{ $patient->nom_complet }}</div>
                </div>

                @if($patient->num_telephone)
                    <div class="info-item">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">{{ $patient->num_telephone }}</div>
                    </div>
                @endif

                @if($patient->age)
                    <div class="info-item">
                        <div class="info-label">Âge</div>
                        <div class="info-value">{{ $patient->age }} ans</div>
                    </div>
                @endif
            </div>

            @if($patient->maladies)
                <div class="medical-info">
                    <div class="info-label">Conditions Médicales</div>
                    <div class="medical-content">
                        <i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>
                        {{ $patient->maladies }}
                    </div>
                </div>
            @endif
        </div>

        <!-- Appointment History -->
        <div class="section-card">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-history"></i>
                    Historique des Rendez-vous
                </h2>
                <a href="{{ route('rendezvous.create') }}?patient_id={{ $patient->id }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouveau RDV
                </a>
            </div>

            <div class="rdv-timeline">
                @forelse($rendezVous as $rdv)
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $rdv->statut }}"></div>
                        <div class="timeline-content">
                            <div class="rdv-date">
                                {{ $rdv->date_heure->format('d/m/Y à H:i') }}
                            </div>
                            <div class="rdv-details">
                                @if($rdv->dentiste)
                                    Dr. {{ $rdv->dentiste->nom_dentiste }} •
                                @endif
                                <span class="rdv-motif">{{ ucfirst(str_replace('_', ' ', $rdv->motif)) }}</span>
                                • Statut: 
                                @if($rdv->statut === 'termine')
                                    <span style="color: #10b981; font-weight: 600;">Terminé</span>
                                @else
                                    <span style="color: #f59e0b; font-weight: 600;">Prévu</span>
                                @endif
                            </div>
                            
                            <div class="rdv-actions">
                                <a href="{{ route('rendezvous.edit', $rdv->id) }}" 
                                   class="btn-icon btn-edit btn-sm" 
                                   title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($rdv->statut === 'prevu')
                                    <form action="{{ route('rendezvous.update-status', $rdv->id) }}" 
                                          method="POST" 
                                          style="display: inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="statut" value="termine">
                                        <button type="submit" 
                                                class="btn-icon btn-sm" 
                                                style="background: #dcfce7; color: #166534;" 
                                                title="Marquer terminé">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Aucun rendez-vous</h3>
                        <p>Ce patient n'a encore aucun rendez-vous</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        @if($stats['prochain_rdv'])
            <div class="next-rdv-card">
                <div class="next-rdv-title">Prochain rendez-vous</div>
                <div class="next-rdv-date">
                    {{ $stats['prochain_rdv']->date_heure->format('d/m/Y à H:i') }}
                </div>
                <div class="next-rdv-details">
                    {{ ucfirst(str_replace('_', ' ', $stats['prochain_rdv']->motif)) }}
                    @if($stats['prochain_rdv']->dentiste)
                        • Dr. {{ $stats['prochain_rdv']->dentiste->nom_dentiste }}
                    @endif
                </div>
            </div>
        @endif

        <div class="section-card">
            <div class="section-header">
                <h3 class="section-title">
                    <i class="fas fa-bolt"></i>
                    Actions Rapides
                </h3>
            </div>
            
            <div class="quick-actions">
                <a href="{{ route('rendezvous.create') }}?patient_id={{ $patient->id }}" 
                   class="quick-action-btn primary">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Nouveau Rendez-vous</span>
                </a>
                
                <a href="{{ route('patients.edit', $patient->id) }}" 
                   class="quick-action-btn secondary">
                    <i class="fas fa-user-edit"></i>
                    <span>Modifier Informations</span>
                </a>
                
                <a href="{{ route('patients.index') }}" 
                   class="quick-action-btn secondary">
                    <i class="fas fa-arrow-left"></i>
                    <span>Retour à la Liste</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection