@extends('layouts.app')

@section('title', 'Modifier Patient - Dentina')

@push('styles')
<link href="{{ asset('css/patients.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="page-header">
    <h1 class="page-title">Modifier Patient</h1>
    <p class="page-subtitle">Modifier les informations de {{ $patient->nom_complet }}</p>
</div>


<div class="card">
    <div class="card-header">
        <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Formulaire de Modification</h3>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('patients.show', $patient->id) }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-eye"></i> Voir Profil
            </a>
            <a href="{{ route('patients.index') }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-arrow-left"></i> Retour Liste
            </a>
        </div>
    </div>

    <div style="padding: 24px;">
        <form method="POST" action="{{ route('patients.update', $patient->id) }}" id="patientForm">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-user"></i>
                    Informations Personnelles
                </h4>
                
                <div class="form-grid">
                    <!-- Nom complet -->
                    <div class="form-group">
                        <label for="nom_complet" class="form-label">
                            Nom Complet <span class="required">*</span>
                        </label>
                        <input
                            type="text"
                            id="nom_complet"
                            name="nom_complet"
                            class="form-input @error('nom_complet') error @enderror"
                            value="{{ old('nom_complet', $patient->nom_complet) }}"
                            placeholder="Ex: Ahmed Ben Ali"
                            required
                            maxlength="100">
                        @error('nom_complet')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="input-help">Le nom complet du patient tel qu'il apparaît sur ses documents</div>
                    </div>

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="num_telephone" class="form-label">Numéro de Téléphone</label>
                        <div class="phone-input">
                            <input
                                type="tel"
                                id="num_telephone"
                                name="num_telephone"
                                class="form-input @error('num_telephone') error @enderror"
                                value="{{ old('num_telephone', $patient->num_telephone) }}"
                                placeholder="123 456 789"
                                maxlength="10">
                        </div>
                        @error('num_telephone')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="input-help">Numéro principal pour contacter le patient</div>
                    </div>

                    <div class="form-group">
                        <label for="age" class="form-label">Âge</label>
                        <input
                            type="number"
                            id="age"
                            name="age"
                            class="form-input @error('age') error @enderror"
                            value="{{ old('age', $patient->age) }}"
                            placeholder="Ex: 35"
                            min="0"
                            max="150">
                        @error('age')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="input-help">Âge actuel du patient en années</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-notes-medical"></i>
                    Informations Médicales
                </h4>

                <div class="form-grid">
                    <!-- Maladies -->
                    <div class="form-group">
                        <label for="maladies" class="form-label">Conditions Médicales & Allergies</label>
                        <textarea
                            id="maladies"
                            name="maladies"
                            class="form-textarea @error('maladies') error @enderror"
                            rows="4"
                            placeholder="Ex: Diabète type 2, Allergie à la pénicilline, Hypertension..."
                            maxlength="500">{{ old('maladies', $patient->maladies) }}</textarea>
                        @error('maladies')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="character-count" id="maladies-count">0/500 caractères</div>
                        <div class="input-help">Listez toutes les conditions médicales importantes, allergies et traitements en cours</div>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label">Notes & Diagnostics</label>
                        <textarea
                            id="notes"
                            name="notes"
                            class="form-textarea @error('notes') error @enderror"
                            rows="4"
                            placeholder="Ex: Patient anxieux, préfère RDV matinaux, diagnostic précédent de carie 16..."
                            maxlength="500">{{ old('notes', $patient->notes) }}</textarea>
                        @error('notes')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                        <div class="character-count" id="notes-count">0/500 caractères</div>
                        <div class="input-help">Espace libre pour diagnostics, observations et notes importantes</div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Sauvegarder les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Danger Zone -->
<div class="danger-zone">
    <div class="danger-header">
        <h4 class="danger-title">
            <i class="fas fa-exclamation-triangle"></i>
            Zone de Danger
        </h4>
    </div>
    <div class="danger-content">
        <p class="danger-description">
            <strong>Attention :</strong> La suppression de ce patient entraînera la perte définitive de :
        </p>
        <ul style="margin: 12px 0 16px 20px; color: #7f1d1d;">
            <li>Toutes les informations personnelles et médicales</li>
            <li>L'historique complet des rendez-vous</li>
            <li>Toutes les notes et diagnostics associés</li>
        </ul>
        <p class="danger-description">
            Cette action est <strong>irréversible</strong> et ne peut pas être annulée.
        </p>
        
        <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" 
              onsubmit="return confirmDelete()">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #dc2626; color: white;">
                <i class="fas fa-trash-alt"></i>
                Supprimer définitivement ce patient
            </button>
        </form>
    </div>
</div>

<!-- Save Indicator -->
<div id="saveIndicator" class="save-indicator">
    <i class="fas fa-check"></i> Modifications sauvegardées !
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textareas = ['maladies', 'notes'];
    textareas.forEach(function(id) {
        const textarea = document.getElementById(id);
        const counter = document.getElementById(id + '-count');
        
        function updateCounter() {
            const count = textarea.value.length;
            const max = textarea.getAttribute('maxlength');
            counter.textContent = count + '/' + max + ' caractères';
            
            counter.className = 'character-count';
            if (count > max * 0.8) {
                counter.classList.add('warning');
            }
            if (count >= max) {
                counter.classList.add('error');
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter(); 
    });
    
    const form = document.getElementById('patientForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('input[required], textarea[required]');
        let isValid = true;
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
        }
    });
    
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(function(input) {
        let saveTimeout;
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(function() {
                // Simulation d'une sauvegarde automatique
                // showSaveIndicator();
            }, 2000);
        });
    });
});

function confirmDelete() {
    const patientName = "{{ $patient->nom_complet }}";
    const confirmation = confirm(
        `⚠️ ATTENTION : Suppression définitive\n\n` +
        `Vous êtes sur le point de supprimer le patient "${patientName}".\n\n` +
        `Cette action supprimera :\n` +
        `• Toutes les informations personnelles\n` +
        `• L'historique des rendez-vous\n` +
        `• Toutes les notes médicales\n\n` +
        `Cette action est IRRÉVERSIBLE.\n\n` +
        `Confirmez-vous la suppression ?`
    );
    
    if (confirmation) {
        return confirm(
            `Dernière confirmation :\n\n` +
            `Tapez "SUPPRIMER" pour confirmer la suppression définitive du patient "${patientName}".`
        );
    }
    
    return false;
}

function showSaveIndicator() {
    const indicator = document.getElementById('saveIndicator');
    indicator.classList.add('show');
    setTimeout(function() {
        indicator.classList.remove('show');
    }, 3000);
}
</script>
@endpush