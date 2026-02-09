@extends('layout.app')

@section('title', 'Liste des Produits')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Liste des Produits</h3>
        <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createProduitModal">
            <i class="bi bi-plus-lg me-2"></i> Nouveau Produit
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <form action="{{ route('produits.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="nom" class="form-control border-start-0 ps-0" placeholder="Rechercher par nom..." value="{{ request('nom') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    @if(request('nom'))
                        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Description</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Catégorie</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits as $produit)
                            <tr class="text-center">
                                <td class="fw-bold">{{ $produit->nom }}</td>
                                <td>{{ Str::limit($produit->description, 40) }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        {{ number_format($produit->prix, 2) }} DH
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $produit->stock > 10 ? 'bg-info' : 'bg-warning' }} text-dark">
                                        {{ $produit->stock }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $produit->categorie->intitule ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#showProduitModal{{ $produit->id }}" title="Afficher">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editProduitModal{{ $produit->id }}" title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $produit->id }}" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @include('produit.edit')
                                    @include('produit.show')
                                    @include('produit.delete')
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-box-seam display-4 d-block mb-3"></i>
                                    Aucun produit trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $produits->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@include('produit.create')
@endsection
