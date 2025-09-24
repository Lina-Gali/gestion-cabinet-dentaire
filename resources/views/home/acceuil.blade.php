@extends('layouts.app')

@section('title', 'Tableau de Bord - Dentina')

@push('styles')
<style>
    .dashboard-greeting {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 32px;
        border-radius: 16px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-greeting::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(50%, -50%);
    }

    .greeting-content {
        position: relative;
        z-index: 1;
    }

    .greeting-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .greeting-subtitle {
        font-size: 16px;
        opacity: 0.9;
    }

    .greeting-date {
        font-size: 14px;
        opacity: 0.8;
        margin-top: 4px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card.primary {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
    }

    .stat-card.success {
        background: linear-gradient(135deg, #10b981, #047857);
        color: white;
    }

    .stat-card.warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .stat-card.info {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .stat-title {
        font-size: 14px;
        font-weight: 600;
        opacity: 0.9;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-card:not(.primary):not(.success):not(.warning):not(.info) .stat-icon {
        background: #f8fafc;
        color: #64748b;
    }

    .stat-number {
        font-size: 32px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        opacity: 0.8;
    }

    .stat-progress {
        margin-top: 12px;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        overflow: hidden;
    }

    .stat-card:not(.primary):not(.success):not(.warning):not(.info) .stat-progress {
        background: #e2e8f0;
    }

    .stat-progress-bar {
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 2px;
        transition: width 0.3s ease;
    }

    .stat-card:not(.primary):not(.success):not(.warning):not(.info) .stat-progress-bar {
        background: #3b82f6;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 32px;
        margin-bottom: 32px;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    .section-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .section-header {
        padding: 24px 24px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .today-schedule {
        padding: 0;
    }

    .schedule-item {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background 0.2s;
    }

    .schedule-item:hover {
        background: #f8fafc;
    }

    .schedule-item:last-child {
        border-bottom: none;
    }

    .schedule-time {
        min-width: 80px;
        text-align: center;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
    }

    .schedule-time.prevu {
        background: #fef3c7;
        color: #92400e;
    }

    .schedule-time.termine {
        background: #dcfce7;
        color: #166534;
    }

    .patient-info {
        flex: 1;
    }

    .patient-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .patient-motif {
        font-size: 13px;
        color: #64748b;
    }

    .dentist-info {
        font-size: 13px;
        color: #64748b;
        text-align: right;
    }

    .empty-state {
        padding: 48px 24px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .upcoming-list {
        padding: 0;
    }

    .upcoming-item {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: between;
    }

    .upcoming-item:last-child {
        border-bottom: none;
    }

    .upcoming-date {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 4px;
    }

    .upcoming-patient {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        padding: 24px;
    }

    .quick-stat {
        text-align: center;
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
    }

    .quick-stat-number {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .quick-stat-label {
        font-size: 12px;
        color: #64748b;
    }
</style>
@endpush

@section('content')
<!-- Greeting Section -->
<div class="dashboard-greeting">
    <div class="greeting-content">
        <h1 class="greeting-title">
            Bonjour ! Bienvenue sur Dentina
        </h1>
        <p class="greeting-subtitle">
            Voici un aperçu de vos rendez-vous du jour
        </p>
        <div class="greeting-date">
            {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-header">
            <span class="stat-title">Rendez-vous aujourd'hui</span>
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
        </div>
        <div class="stat-number">{{ $rendezVous->count() }}</div>
        <div class="stat-label">RDV programmés</div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <span class="stat-title">Consultations</span>
            <div class="stat-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
        </div>
        <div class="stat-number">{{ $rendezVous->where('motif', 'consultation')->count() }}</div>
        <div class="stat-label">Aujourd'hui</div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <span class="stat-title">Extractions</span>
            <div class="stat-icon">
                <i class="fas fa-tooth"></i>
            </div>
        </div>
        <div class="stat-number">{{ $rendezVous->where('motif', 'extraction')->count() }}</div>
        <div class="stat-label">Aujourd'hui</div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <span class="stat-title">Soins dentaires</span>
            <div class="stat-icon">
                <i class="fas fa-teeth-open"></i>
            </div>
        </div>
        <div class="stat-number">{{ $rendezVous->where('motif', 'soins_dentaires')->count() }}</div>
        <div class="stat-label">Aujourd'hui</div>
    </div>
</div>

<!-- Planning du jour -->
<div class="section-card">
    <div class="section-header">
        <h2 class="section-title">
            <i class="fas fa-calendar-day"></i>
            Planning d'aujourd'hui
        </h2>
        <a href="{{ route('rendezvous.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau RDV
        </a>
    </div>
    
    <div class="today-schedule">
        @forelse($rendezVous as $rdv)
            <div class="schedule-item">
                <div class="schedule-time {{ $rdv->statut ?? '' }}">
                    {{ $rdv->date_heure->format('H:i') }}
                </div>
                <div class="patient-info">
                    <div class="patient-name">{{ $rdv->patient->nom_complet }}</div>
                    <div class="patient-motif">{{ ucfirst(str_replace('_', ' ', $rdv->motif)) }}</div>
                </div>
                <div class="dentist-info">
                    {{ $rdv->dentiste ? 'Dr. ' . $rdv->dentiste->nom_dentiste : 'Non assigné' }}
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('rendezvous.edit', $rdv->id) }}" 
                       class="btn-icon btn-edit btn-sm" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>Aucun rendez-vous aujourd'hui</h3>
                <p>Profitez de cette journée plus calme !</p>
                <a href="{{ route('rendezvous.create') }}" class="btn btn-primary" style="margin-top: 16px;">
                    <i class="fas fa-plus"></i> Planifier un RDV
                </a>
            </div>
        @endforelse
    </div>
</div>

<div style="margin-top: 20px;">
    {{ $rendezVous->links() }}
</div>
@endsection