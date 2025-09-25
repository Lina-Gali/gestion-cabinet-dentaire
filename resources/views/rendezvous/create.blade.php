@extends('layouts.app')

@section('title', 'Nouveau Rendez-Vous')

@push('styles')
<link href="{{ asset('css/rdv.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Créer un Rendez-Vous</h1>
    <p class="page-subtitle">Remplissez les informations pour programmer un nouveau rendez-vous</p>
</div>

<div class="card">
    <div class="card-header">
        <h2>Formulaire</h2>
    </div>

    <div class="card-body">
        <form action="{{ route('rendezvous.store') }}" method="POST" class="form-grid" autocomplete="off">
            @csrf

            <!-- Patient -->
            <div class="form-group patient-field">
                <label for="patient_search">Patient <span class="required">*</span></label>

                <div class="search-container">
                    <input type="text"
                        id="patient_search"
                        class="search-input"
                        placeholder="Rechercher par nom complet..."
                        aria-autocomplete="list"
                        aria-controls="patient_results"
                        autocomplete="off">

                    <div class="search-icon" aria-hidden="true">
                        <i class="fas fa-search"></i>
                    </div>

                    <ul id="patient_results" class="dropdown-list" role="listbox" aria-labelledby="patient_search"></ul>
                </div>

                <input type="hidden" name="patient_id" id="patient_id" required>

                <div class="selected-patient" id="selected_patient" aria-live="polite">
                    <i class="fas fa-user"></i>
                    <span class="patient-name"></span>
                    <button type="button" class="clear-selection" onclick="clearPatientSelection()" title="Retirer la sélection">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                @error('patient_id')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Date & Heure -->
            <div class="form-group">
                <label for="date_heure">Date et Heure <span class="required">*</span></label>
                <input type="datetime-local"
                    name="date_heure"
                    id="date_heure"
                    class="search-input"
                    value="{{ old('date_heure') }}"
                    required>
                @error('date_heure')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            <!-- Dentiste -->
            <div class="form-group">
                <label for="dentiste_id">Dentiste <span class="required">*</span></label>
                <select name="dentiste_id" id="dentiste_id" class="filter-select" required>
                    <option value="">-- Sélectionner un dentiste --</option>
                    @foreach($dentistes as $dentiste)
                    <option value="{{ $dentiste->id }}">{{ $dentiste->nom_dentiste }}</option>
                    @endforeach
                </select>
                @error('dentiste_id')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Motif -->
            <div class="form-group">
                <label for="motif">Motif <span class="required">*</span></label>
                <select name="motif" id="motif" class="filter-select" required>
                    <option value="">-- Sélectionner un motif --</option>
                    <option value="consultation" {{ old('motif') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                    <option value="extraction" {{ old('motif') == 'extraction' ? 'selected' : '' }}>extraction</option>
                    <option value="soins_dentaires" {{ old('motif') == 'soins_dentaires' ? 'selected' : '' }}>Soins dentaires</option>
                    <option value="prothese" {{ old('motif') == 'prothese' ? 'selected' : '' }}>prothese</option>
                </select>
                @error('motif')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>



            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i> Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('patient_search');
        const resultsList = document.getElementById('patient_results');
        const hiddenInput = document.getElementById('patient_id');
        const selectedPatientDiv = document.getElementById('selected_patient');
        const searchContainer = document.querySelector('.search-container');

        let searchTimeout = null;
        let highlightedIndex = -1;

        function showResults() {
            resultsList.style.display = 'block';
        }

        function hideResults() {
            resultsList.style.display = 'none';
            highlightedIndex = -1;
        }

        // Requête debounce
        searchInput.addEventListener('input', function() {
            const q = this.value.trim();
            hiddenInput.value = '';
            selectedPatientDiv.style.display = 'none';

            // clear previous
            clearTimeout(searchTimeout);
            resultsList.innerHTML = '';

            if (q.length <= 1) {
                hideResults();
                return;
            }

            // loading indicator
            resultsList.innerHTML = '<li class="dropdown-item loading"><i class="fas fa-spinner fa-spin"></i> Recherche...</li>';
            showResults();

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('patients.search') }}?q=${encodeURIComponent(q)}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network error');
                        return res.json();
                    })
                    .then(data => {
                        resultsList.innerHTML = '';
                        if (!Array.isArray(data) || data.length === 0) {
                            resultsList.innerHTML = '<li class="dropdown-item loading">Aucun patient trouvé</li>';
                            return;
                        }

                        data.forEach((p, idx) => {
                            const li = document.createElement('li');
                            li.className = 'dropdown-item selectable';
                            const fullname = (p.nom_complet) ? p.nom_complet : `${p.nom || ''} ${p.prenom || ''}`.trim();
                            li.innerHTML = `<div class="patient-item"><i class="fas fa-user"></i><span class="patient-name">${fullname}</span></div>`;
                            li.dataset.patientId = p.id;
                            li.dataset.patientName = fullname;

                            li.addEventListener('click', () => {
                                selectPatient(p.id, fullname);
                            });

                            resultsList.appendChild(li);
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        resultsList.innerHTML = '<li class="dropdown-item error">Erreur lors de la recherche</li>';
                    });
            }, 260);
        });

        // sélection
        function selectPatient(id, name) {
            hiddenInput.value = id;
            selectedPatientDiv.querySelector('.patient-name').textContent = name;
            selectedPatientDiv.style.display = 'flex';
            searchInput.value = '';
            hideResults();
        }

        // clear
        window.clearPatientSelection = function() {
            hiddenInput.value = '';
            selectedPatientDiv.style.display = 'none';
            searchInput.value = '';
            searchInput.focus();
        }

        // Fermer quand clic à l'extérieur
        document.addEventListener('click', (e) => {
            if (!searchContainer.contains(e.target) && !resultsList.contains(e.target)) {
                hideResults();
            }
        });

        // Navigation clavier (ArrowUp/Down/Enter/Escape)
        searchInput.addEventListener('keydown', (e) => {
            const items = Array.from(resultsList.querySelectorAll('.dropdown-item.selectable'));
            if (items.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                highlightedIndex = Math.min(highlightedIndex + 1, items.length - 1);
                updateHighlight(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                highlightedIndex = Math.max(highlightedIndex - 1, 0);
                updateHighlight(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (highlightedIndex >= 0 && items[highlightedIndex]) {
                    items[highlightedIndex].click();
                }
            } else if (e.key === 'Escape') {
                hideResults();
            }
        });

        function updateHighlight(items) {
            items.forEach((it, i) => {
                it.classList.toggle('highlighted', i === highlightedIndex);
                if (i === highlightedIndex) it.scrollIntoView({
                    block: 'nearest'
                });
            });
        }
    });
</script>
@endpush