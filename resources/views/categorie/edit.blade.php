@extends('layout.app')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Modifier la Catégorie</h3>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('categories.update', $categorie->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="intitule" class="form-label fw-bold">Intitulé</label>
                    <input type="text" class="form-control" id="intitule" name="intitule" value="{{ old('intitule', $categorie->intitule) }}" required>
                    @error('intitule')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $categorie->description) }}</textarea>
                    @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil-square me-1"></i> Modifier
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
