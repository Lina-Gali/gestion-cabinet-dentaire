@extends('layouts.app')

@section('title', 'Nouveau Rendez-Vous')

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

@push('styles')
<style>
    /* Card centré + espacement */
    .card {
        max-width: 920px;
        margin: 18px auto;
        border-radius: 12px;
        overflow: hidden;
    }

    .card-body {
        padding: 22px;
    }

    .card-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e6eef6;
        background: #ffffff;
    }

    .page-header {
        max-width: 920px;
        margin: 18px auto 8px;
    }

    .page-title {
        font-size: 22px;
        margin-bottom: 4px;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 13px;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        gap: 18px;
        grid-template-columns: 1fr 1fr;
        align-items: start;
    }

    /* Le champ patient doit prendre toute la largeur */
    .patient-field {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #374151;
        font-size: 14px;
    }

    .required {
        color: #ef4444;
    }

    /* Input style */
    .search-input,
    .filter-select {
        padding: 12px 16px;
        border: 1px solid #e6eef6;
        border-radius: 10px;
        font-size: 14px;
        background: #ffffff;
        width: 100%;
        box-sizing: border-box;
    }

    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 6px 18px rgba(59, 130, 246, 0.08);
    }

    /* Search container + icon */
    .search-container {
        position: relative;
        width: 100%;
    }

    .search-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    /* Dropdown results */
    .dropdown-list {
        list-style: none;
        margin: 8px 0 0;
        padding: 6px;
        border: 1px solid #e6eef6;
        border-radius: 10px;
        max-height: 220px;
        overflow-y: auto;
        background: #fff;
        position: absolute;
        z-index: 1100;
        width: 100%;
        box-shadow: 0 8px 40px rgba(16, 24, 40, 0.06);
        display: none;
    }

    .dropdown-item {
        padding: 10px 12px;
        cursor: pointer;
        border-radius: 8px;
        margin: 2px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0f172a;
    }

    .dropdown-item:hover,
    .dropdown-item.highlighted {
        background: #f8fafc;
    }

    .dropdown-item.loading {
        color: #6b7280;
        cursor: default;
        font-style: italic;
    }

    .patient-item i {
        color: #6b7280;
    }

    /* Selected patient box */
    .selected-patient {
        margin-top: 8px;
        display: none;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        border-radius: 10px;
        color: #075985;
        font-weight: 600;
    }

    .clear-selection {
        margin-left: auto;
        background: transparent;
        border: none;
        color: #475569;
        cursor: pointer;
        padding: 6px;
        border-radius: 6px;
    }

    .clear-selection:hover {
        background: rgba(0, 0, 0, 0.04);
    }

    .error-message {
        color: #ef4444;
        font-size: 12px;
        margin-top: 6px;
    }

    /* Actions */
    .form-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 8px;
    }

    .btn {
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: #3b82f6;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.18);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #0f172a;
        border: 1px solid #e6eef6;
    }

    /* responsive */
    @media (max-width: 800px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            justify-content: stretch;
            flex-direction: column-reverse;
            gap: 10px;
        }
    }
</style>
@endpush

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