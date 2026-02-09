@extends('layout.app')

@section('title', 'Liste des Commandes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Liste des Commandes</h3>
        <a href="{{ route('commandes.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Nouvelle Commande
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-white py-3">
            <form action="{{ route('commandes.search') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="recherche" class="form-control" placeholder="Recherche (ID, Client...)" value="{{ request('recherche') }}">
                </div>

                <div class="col-md-2">
                    <input type="number" name="min_amount" class="form-control" placeholder="Montant Min" value="{{ request('min_amount') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}" placeholder="Date début">
                </div>
                <!-- <div class="col-md-2">
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}" placeholder="Date fin">
                </div> -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Rechercher</button>
                    <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Client</th>
                            <th scope="col">Date</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Montant Total</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commandes as $commande)
                            <tr class="text-center">
                                <td class="fw-bold">#{{ $commande->id }}</td>
                                <td>{{ $commande->client->nom }} {{ $commande->client->prenom }}</td>
                                <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary">Brouillon</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ number_format($commande->montant_total, 2) }} DH
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $commande->id }}" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @include('commande.delete')
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Aucune commande trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($commandes->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $commandes->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
