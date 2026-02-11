@extends('layout.app')

@section('title', 'Modifier le Produit')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Modifier le Produit</h3>
        <a href="{{ route('produits.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nom" class="form-label fw-bold">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $produit->nom) }}" required>
                    @error('nom')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="imageURL" class="form-label fw-bold">Image</label>
                    @if($produit->imageURL)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $produit->imageURL) }}" alt="Image du produit" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" class="form-control" id="imageURL" name="imageURL" accept="image/*">
                    @error('imageURL')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="2">{{ old('description', $produit->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="prix" class="form-label fw-bold">Prix (DH)</label>
                        <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="{{ old('prix', $produit->prix) }}" required>
                        @error('prix')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label fw-bold">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $produit->stock) }}" required>
                        @error('stock')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="categorie_id" class="form-label fw-bold">Catégorie</label>
                        <select class="form-select" id="categorie_id" name="categorie_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}" {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->intitule }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </button>
                    <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
