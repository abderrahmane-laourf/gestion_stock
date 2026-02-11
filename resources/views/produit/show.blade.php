@extends('layout.app')

@section('title', 'Détails du Produit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Détails du Produit</h3>
        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-3">
                @if($produit->imageURL)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $produit->imageURL) }}" alt="Image du produit" class="img-fluid rounded" style="max-height: 300px;">
                    </div>
                @endif
                <label class="form-label fw-bold">Nom</label>
                <p class="form-control-plaintext">{{ $produit->nom }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <p class="form-control-plaintext">{{ $produit->description ?: 'Aucune description disponible.' }}</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Prix</label>
                    <p class="form-control-plaintext">{{ number_format($produit->prix, 2) }} DH</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Stock</label>
                    <p class="form-control-plaintext">{{ $produit->stock }} unités</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Catégorie</label>
                    <p class="form-control-plaintext">{{ $produit->categorie->intitule ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Modifier
                </a>
                <a href="{{ route('produits.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>
@endsection
