@extends('layout.app')

@section('title', 'Liste des Catégories')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Liste des Catégories</h3>
        <a href="{{ route('categories.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-lg me-2"></i> Nouvelle Catégorie
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <form action="{{ route('categories.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-auto flex-grow-1">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="intitule" class="form-control border-start-0 ps-0" placeholder="Rechercher par intitulé..." value="{{ request('intitule') }}">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    @if(request('intitule'))
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-secondary text-center">
                        <tr>
                            <th scope="col">Intitulé</th>
                            <th scope="col">Description</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $categorie)
                            <tr class="text-center">
                                <td class="fw-bold">{{ $categorie->intitule }}</td>
                                <td>{{ Str::limit($categorie->description, 50) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#showCategorieModal{{ $categorie->id }}" title="Afficher">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editCategorieModal{{ $categorie->id }}" title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $categorie->id }}" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    @include('categorie.edit')
                                    @include('categorie.show')
                                    @include('categorie.delete')
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-tags display-4 d-block mb-3"></i>
                                    Aucune catégorie trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection