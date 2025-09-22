@extends('layouts.app')

@section('title', 'Patients - Dentina')

@section('content')
<div class="page-header">
    <h1 class="page-title">Patients</h1>
    <p class="page-subtitle">Showing {{ $patients->count() }} of {{ $patients->total() }}</p>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-filters">
            <form method="GET" action="{{ route('patients.index') }}" class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Rechercher..."
                    value="{{ request('search') }}">
            </form>
        </div>

        <a href="{{ route('patients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Ajouter Patient
        </a>
    </div>

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Numéro de Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->id }}</td>
                    <td>{{ $patient->nom_complet }}</td>
                    <td>{{ $patient->num_telephone  ?? 'N/A' }}</td>
                    <td>
                        <div class="action-buttons">
                            <form method="POST" action="{{ route('patients.destroy', $patient->id) }}" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn-icon btn-edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="{{ route('patients.show', $patient->id) }}" class="btn-icon btn-view" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #94a3b8;">
                        <i class="fas fa-users" style="font-size: 48px; margin-bottom: 16px; display: block;"></i>
                        Aucun patient trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($patients->hasPages())
    <div class="pagination">
        {{ $patients->links() }}
    </div>
    @endif
</div>

@if(session('success'))
<div style="position: fixed; top: 20px; right: 20px; background: #10b981; color: white; padding: 16px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); z-index: 1000;">
    <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
    {{ session('success') }}
</div>

<script>
    setTimeout(function() {
        const successMessage = document.querySelector('[style*="position: fixed"]');
        if (successMessage) {
            successMessage.style.opacity = '0';
            successMessage.style.transform = 'translateX(100%)';
            setTimeout(() => successMessage.remove(), 300);
        }
    }, 5000);
</script>
@endif

@endSection

@push('scripts')
<script>
    let searchTimeout;
    const searchInput = document.querySelector('.search-input');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
    }

    // Auto-submit des formulaires de filtres
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            // Créer un formulaire avec la recherche actuelle et le nouveau filtre
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("patients.index") }}';

            // Ajouter l'input de recherche s'il existe
            const searchValue = document.querySelector('.search-input').value;
            if (searchValue) {
                const searchField = document.createElement('input');
                searchField.type = 'hidden';
                searchField.name = 'search';
                searchField.value = searchValue;
                form.appendChild(searchField);
            }

            // Ajouter le filtre actuel
            const filterField = document.createElement('input');
            filterField.type = 'hidden';
            filterField.name = this.name;
            filterField.value = this.value;
            form.appendChild(filterField);

            document.body.appendChild(form);
            form.submit();
        });
    });
</script>
@endpush