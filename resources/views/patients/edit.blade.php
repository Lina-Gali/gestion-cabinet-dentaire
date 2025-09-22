@extends('layouts.app')

@section('title', 'Modifier Patient - Dentina')

@section('content')
<div class="page-header">
    <h1 class="page-title">Modifier Patient</h1>
    <p class="page-subtitle">Modifier les informations de {{ $patient->nom_complet }}</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Informations du Patient</h3>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('patients.show', $patient->id) }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-eye"></i>
                Voir
            </a>
            <a href="{{ route('patients.index') }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                <i class="fas fa-arrow-left"></i>
                Retour
            </a>
        </div>
    </div>

    <div style="padding: 24px;">
        <!-- ✅ Formulaire de mise à jour -->
        <form method="POST" action="{{ route('patients.update', $patient->id) }}">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                <!-- Nom complet -->
                <div class="form-group">
                    <label for="nom_complet" class="form-label">
                        Nom Complet <span style="color: #ef4444;">*</span>
                    </label>
                    <input
                        type="text"
                        id="nom_complet"
                        name="nom_complet"
                        class="form-input @error('nom_complet') error @enderror"
                        value="{{ old('nom_complet', $patient->nom_complet) }}"
                        placeholder="Entrez le nom complet"
                        required>
                    @error('nom_complet')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="form-group">
                    <label for="telephone" class="form-label">Numéro de Téléphone</label>
                    <input
                        type="tel"
                        id="telephone"
                        name="telephone"
                        class="form-input @error('telephone') error @enderror"
                        value="{{ old('telephone', $patient->telephone ?? $patient->num_telephone) }}"
                        placeholder="0 123 456 789">
                    @error('telephone')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Âge -->
                <div class="form-group">
                    <label for="age" class="form-label">Âge</label>
                    <input
                        type="number"
                        id="age"
                        name="age"
                        class="form-input @error('age') error @enderror"
                        value="{{ old('age', $patient->age) }}"
                        placeholder="25"
                        min="0"
                        max="150">
                    @error('age')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Maladies -->
            <div class="form-group" style="margin-top: 24px;">
                <label for="maladies" class="form-label">Maladies / Conditions Médicales</label>
                <textarea
                    id="maladies"
                    name="maladies"
                    class="form-textarea @error('maladies') error @enderror"
                    rows="4"
                    placeholder="Décrivez les conditions médicales, allergies, ou maladies chroniques...">{{ old('maladies', $patient->maladies) }}</textarea>
                @error('maladies')
                <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Boutons d'action du formulaire principal -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <a href="{{ route('patients.show', $patient->id) }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Sauvegarder les modifications
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ Formulaire de suppression placé séparément en dessous -->
<div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px;">
        <h4 style="margin: 0 0 8px 0; color: #dc2626; font-size: 16px; font-weight: 600;">
            <i class="fas fa-exclamation-triangle"></i>
            Zone de danger
        </h4>
        <p style="margin: 0 0 16px 0; color: #7f1d1d; font-size: 14px;">
            Cette action supprimera définitivement le patient et toutes ses données associées.
        </p>
        <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient? Cette action est irréversible.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #dc2626; color: white;">
                <i class="fas fa-trash"></i>
                Supprimer définitivement ce patient
            </button>
        </form>
    </div>
</div>

<style>
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
        font-size: 14px;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
        font-family: 'Mulish', sans-serif;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-input.error,
    .form-textarea.error {
        border-color: #ef4444;
    }

    .error-message {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: #ef4444;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    select.form-input {
        cursor: pointer;
    }
</style>
@endsection