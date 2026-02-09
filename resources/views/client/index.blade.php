@extends('layout.app')

@section('title', 'Liste des Clients')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Liste des Clients</h3>
        <a href="{{ route('clients.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i> Nouveau Client
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <form action="{{ route('clients.index') }}" method="GET" class="row g-3 align-items-center">
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
                        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
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
                            <th scope="col">Prénom</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Adresse</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr class="text-center">
                                <td>{{ $client->nom }}</td>
                                <td>{{ $client->prenom }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-telephone me-1"></i> {{ $client->telephone }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($client->adresse, 30) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-outline-info" title="Afficher">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $client->id }}" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                </div>
                                    @include('client.delete')
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-people display-4 d-block mb-3"></i>
                                    Aucun client trouvé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $clients->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
