@extends('layouts.app')

@section('title', 'Ajouter un Patient - Dentina')

@section('content')
<div class="page-header">
    <h1 class="page-title">Ajouter un Patient</h1>
    <p class="page-subtitle">Créer un nouveau dossier patient</p>
</div>

<div class="card">
    <div class="card-header">
        <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Informations du Patient</h3>
        <a href="{{ route('patients.index') }}" class="btn" style="background: #f1f5f9; color: #64748b;">
            <i class="fas fa-arrow-left"></i>
            Retour à la liste
        </a>
    </div>

    <div style="padding: 24px;">
        <form method="POST" action="{{ route('patients.store') }}">
            @csrf
            
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
                        value="{{ old('nom_complet') }}"
                        placeholder="Entrez le nom complet"
                        required
                    >
                    @error('nom_complet')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Téléphone -->
                <div class="form-group">
                    <label for="num_telephone" class="form-label">Numéro de Téléphone</label>
                    <input 
                        type="tel" 
                        id="num_telephone" 
                        name="num_telephone" 
                        class="form-input @error('num_telephone') error @enderror"
                        value="{{ old('num_telephone') }}"
                        placeholder="+213 123 456 789"
                    >
                    @error('num_telephone')
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
                        value="{{ old('age') }}"
                        placeholder="25"
                        min="0"
                        max="150"
                    >
                    @error('age')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                
            </div>

            <div class="form-group" style="margin-top: 24px;">
                <label for="maladies" class="form-label">Maladies / Conditions Médicales</label>
                <textarea 
                    id="maladies" 
                    name="maladies" 
                    class="form-textarea @error('maladies') error @enderror"
                    rows="4"
                    placeholder="Décrivez les conditions médicales, allergies, ou maladies chroniques..."
                >{{ old('maladies') }}</textarea>
                @error('maladies')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <a href="{{ route('patients.index') }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Créer le Patient
                </button>
            </div>
        </form>
    </div>
</div>



@endSection

