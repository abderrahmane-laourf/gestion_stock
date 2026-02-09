@extends('layout.app')

@section('title', 'Détails de la Catégorie')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Détails de la Catégorie</h3>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold">Intitulé</label>
                <p class="form-control-plaintext">{{ $categorie->intitule }}</p>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <p class="form-control-plaintext">{{ $categorie->description ?: 'Aucune description disponible.' }}</p>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('categories.edit', $categorie->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Modifier
                </a>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>
@endsection
