@extends('layout.app')

@section('title', 'Détails Commande #' . $commande->id)

@section('content')
<div class="container-fluid">
    <!-- Header & Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-primary mb-0">Commande #{{ $commande->id }}</h3>
            @php
                $statusColors = [
                    'brouillon' => 'secondary',
                    'confirmee' => 'primary',
                    'en_cours' => 'info',
                    'livree' => 'success',
                    'annulee' => 'danger'
                ];
                $statusLabels = [
                    'brouillon' => 'Brouillon',
                    'confirmee' => 'Confirmée',
                    'en_cours' => 'En cours',
                    'livree' => 'Livrée',
                    'annulee' => 'Annulée'
                ];
                $color = $statusColors[$commande->statut] ?? 'secondary';
                $label = $statusLabels[$commande->statut] ?? ucfirst($commande->statut);
            @endphp
            <span class="badge bg-{{ $color }}">{{ $label }}</span>
        </div>
        
        <div class="btn-group">
            <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <a href="{{ route('commandes.print', $commande->id) }}" target="_blank" class="btn btn-outline-dark">
                <i class="bi bi-printer"></i> Imprimer
            </a>
        </div>
    </div>

    <!-- Action Bar -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex flex-wrap gap-2 text-center">
            
            <!-- Validate (only if 'brouillon') -->
            @if($commande->statut === 'brouillon')
                <form action="{{ route('commandes.validate', $commande->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Voulez-vous vraiment valider cette commande ? Elle ne sera plus modifiable.')">
                        <i class="bi bi-check-circle"></i> Valider
                    </button>
                </form>
            @endif

            <!-- Cancel (if not 'livree' or 'annulee') -->
            @if(!in_array($commande->statut, ['livree', 'annulee']))
                <form action="{{ route('commandes.cancel', $commande->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Voules-vous vraiment annuler cette commande ?')">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                </form>
            @endif

            <!-- Deliver (only if 'confirmee' or 'en_cours') -->
            @if(in_array($commande->statut, ['confirmee', 'en_cours']))
                <form action="{{ route('commandes.deliver', $commande->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Marquer la commande comme livrée ?')">
                        <i class="bi bi-truck"></i> Livrer
                    </button>
                </form>
            @endif

            <!-- Recalculate -->
            <form action="{{ route('commandes.recalculate', $commande->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-info text-white"><i class="bi bi-calculator"></i> Recalculer</button>
            </form>

            <!-- Notify -->
            <form action="{{ route('commandes.notify', $commande->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-primary"><i class="bi bi-bell"></i> Notifier Client</button>
            </form>
            
             <!-- History -->
            <a href="{{ route('commandes.history', $commande->id) }}" class="btn btn-outline-info">
                <i class="bi bi-clock-history"></i> Historique
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Commande Info -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informations</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('commandes.update', $commande->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Client</label>
                                <select class="form-select" name="client_id" required>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $commande->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }} {{ $client->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date</label>
                            <input type="date" class="form-control" name="date_commande" value="{{ $commande->date_commande->format('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Adresse de livraison</label>
                            <textarea class="form-control" name="adresse_livraison" rows="2" required>{{ $commande->adresse_livraison }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Montant Total</label>
                            <p class="h4 text-success">{{ number_format($commande->montant_total, 2) }} DH</p>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Mettre à jour</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Produits</h5>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addProduitModal">
                        <i class="bi bi-plus-lg"></i> Ajouter Produit
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix Unitaire</th>
                                    <th>Quantité</th>
                                    <th>Sous-total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($commande->produits as $produit)
                                    <tr>
                                        <td class="fw-bold">{{ $produit->nom }}</td>
                                        <td>{{ number_format($produit->pivot->prix_unitaire, 2) }} DH</td>
                                        <td>{{ $produit->pivot->quantite }}</td>
                                        <td class="text-success fw-bold">
                                            {{ number_format($produit->pivot->quantite * $produit->pivot->prix_unitaire, 2) }} DH
                                        </td>
                                        <td>
                                            <form action="{{ route('commandes.removeProduit', ['commandeId' => $commande->id, 'produitId' => $produit->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Retirer ce produit ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Aucun produit dans cette commande</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add Product -->
<div class="modal fade" id="addProduitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('commandes.addProduit', $commande->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Produit</label>
                        <select class="form-select" name="produit_id" required>
                            @foreach($produits as $p)
                                <option value="{{ $p->id }}">{{ $p->nom }} - {{ number_format($p->prix, 2) }} DH (Stock: {{ $p->stock }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantité</label>
                        <input type="number" class="form-control" name="quantite" value="1" min="1" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
