@extends('layout.app')

@section('title', 'Liste des Produits')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Liste des Produits</h3>
        <a href="{{ route('produits.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Nouveau Produit
        </a>
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
                            <th scope="col">Image</th>
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
                                <td>
                                    @if($produit->imageURL)
                                        <img src="{{ asset('storage/' . $produit->imageURL) }}" alt="{{ $produit->nom }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span class="text-muted"><i class="bi bi-image"></i></span>
                                    @endif
                                </td>
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
                                        <a href="{{ route('produits.show', $produit->id) }}" class="btn btn-sm btn-outline-info" title="Afficher">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $produit->id }}" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @include('produit.delete')
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
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
@endsection
