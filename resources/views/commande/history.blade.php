@extends('layout.app')

@section('title', 'Historique Commande #' . $commande->id)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Historique - Commande #{{ $commande->id }}</h3>
        <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la commande
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="timeline">
                @forelse($commande->history as $log)
                    <div class="border-start border-3 border-primary ps-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-1">
                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                            </h5>
                            <small class="text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-0 text-muted">{{ $log->description }}</p>
                    </div>
                @empty
                    <p class="text-center text-muted">Aucun historique disponible.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
