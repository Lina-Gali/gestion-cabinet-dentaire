@extends('layouts.app')

@section('title', $patient->nom_complet . ' - Fakeeh Care Group')

@section('content')
<div class="page-header">
    <h1 class="page-title">{{ $patient->nom_complet }}</h1>
    <p class="page-subtitle">Dossier patient #{{ $patient->id }}</p>
</div>

<div style="display: grid; gap: 24px; grid-template-columns: 2fr 1fr;">
    <!-- Informations principales -->
    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Informations Personnelles</h3>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Modifier
                </a>
                <a href="{{ route('patients.index') }}" class="btn" style="background: #f1f5f9; color: #64748b;">
                    <i class="fas fa-arrow-left"></i>
                    Retour
                </a>
            </div>
        </div>

        <div style="padding: 24px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
                <!-- Nom -->
                <div class="info-item">
                    <label class="info-label">Nom Complet</label>
                    <div class="info-value">{{ $patient->nom_complet }}</div>
                </div>

                <div class="info-item">
                    <label class="info-label">Téléphone</label>
                    <div class="info-value">{{ $patient->num_telephone }}</div>
                </div>

                <div class="info-item">
                    <label class="info-label">Âge</label>
                    <div class="info-value">{{ $patient->age }}</div>
                    </div>
                </div>

            </div>

            @if($patient->maladies)
            <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <label class="info-label">Maladies / Conditions Médicales</label>
                <div class="info-value" style="margin-top: 12px;">
                    <div style="background: #f8fafc; padding: 16px; border-radius: 8px; line-height: 1.6;">
                        {{ $patient->maladies }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Actions Rapides</h3>
            </div>
            <div style="padding: 20px;">
                <button class="btn" style="background: #f0f9ff; color: #0369a1; width: 100%;" data-action="appointment">
                    <i class="fas fa-calendar-plus"></i>
                    Programmer RDV
                </button>

            </div>
        </div>
    </div>

</div>
</div>

<style>
    .info-item {
        margin-bottom: 16px;
    }

    .info-label {
        display: block;
        font-weight: 500;
        color: #6b7280;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .info-value {
        color: #1f2937;
        font-size: 15px;
        font-weight: 400;
    }

    .text-muted {
        color: #9ca3af;
        font-style: italic;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-pink {
        background: #fce7f3;
        color: #be185d;
    }

    .badge-gray {
        background: #f3f4f6;
        color: #6b7280;
    }

    .badge-green {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-orange {
        background: #fed7aa;
        color: #ea580c;
    }

    .badge-purple {
        background: #e9d5ff;
        color: #7c3aed;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content>div {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endSection

@push('scripts')
<script>
    // Actions rapides
    document.addEventListener('DOMContentLoaded', function() {
        // Bouton Admettre
        const admitBtn = document.querySelector('[data-action="admit"]');
        if (admitBtn) {
            admitBtn.addEventListener('click', function() {
                if (confirm('Voulez-vous admettre ce patient?')) {
                    // Ici vous pouvez ajouter la logique pour admettre le patient
                    alert('Patient admis avec succès!');
                }
            });
        }

        // Impression du dossier
        const printBtn = document.querySelector('[data-action="print"]');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                window.print();
            });
        }
    });
</script>
@endpush