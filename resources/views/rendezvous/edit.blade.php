@extends('layouts.app')

@section('title', 'Modifier Rendez-Vous')

@section('content')
<div class="page-header">
    <h1 class="page-title">Modifier un Rendez-Vous</h1>
    <p class="page-subtitle">Mettre à jour les informations du rendez-vous sélectionné</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Formulaire d’édition</h2>
    </div>

    <div class="card-body">
        <form action="{{ route('rendezvous.update', $rendezVous->id) }}" method="POST" class="form-grid" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="patient_id">Patient <span class="required">*</span></label>
                <select name="patient_id" id="patient_id" class="filter-select" required>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ $rendezVous->patient_id == $patient->id ? 'selected' : '' }}>
                            {{ $patient->nom_complet }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="dentiste_id">Dentiste <span class="required">*</span></label>
                <select name="dentiste_id" id="dentiste_id" class="filter-select" required>
                    @foreach($dentistes as $dentiste)
                        <option value="{{ $dentiste->id }}" {{ $rendezVous->dentiste_id == $dentiste->id ? 'selected' : '' }}>
                            {{ $dentiste->nom_dentiste }}
                        </option>
                    @endforeach
                </select>
                @error('dentiste_id')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="date_heure">Date et Heure <span class="required">*</span></label>
                <input type="datetime-local"
                       name="date_heure"
                       id="date_heure"
                       class="search-input"
                       value="{{ old('date_heure', $rendezVous->date_heure->format('Y-m-d\TH:i')) }}"
                       required>
                @error('date_heure')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="motif">Motif <span class="required">*</span></label>
                <select name="motif" id="motif" class="filter-select" required>
                    <option value="consultation" {{ $rendezVous->motif == 'consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="extraction" {{ $rendezVous->motif == 'extraction' ? 'selected' : '' }}>Extraction</option>
                    <option value="soins_dentaires" {{ $rendezVous->motif == 'soins_dentaires' ? 'selected' : '' }}>Soins dentaires</option>
                    <option value="prothese" {{ $rendezVous->motif == 'prothese' ? 'selected' : '' }}>Prothèse</option>
                </select>
                @error('motif')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('rendezvous.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
