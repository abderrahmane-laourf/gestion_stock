@extends('layout.app')

@section('title', 'Détails du Client')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Détails du Client</h3>
        <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nom</label>
                    <p class="form-control-plaintext">{{ $client->nom }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Prénom</label>
                    <p class="form-control-plaintext">{{ $client->prenom }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Téléphone</label>
                    <p class="form-control-plaintext">{{ $client->telephone }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Adresse</label>
                    <p class="form-control-plaintext">{{ $client->adresse }}</p>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Modifier
                </a>
                <a href="{{ route('clients.index') }}" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</div>
@endsection