<div class="modal fade" id="editProduitModal{{ $produit->id }}" tabindex="-1" aria-labelledby="editProduitModalLabel{{ $produit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProduitModalLabel{{ $produit->id }}">Modifier {{ $produit->nom }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{ route('commandes.updateProduit', [$commande->id, $produit->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="quantite{{ $produit->id }}" class="form-label">Quantité</label>
                        <input type="number" class="form-control" id="quantite{{ $produit->id }}" name="quantite" min="1" value="{{ $produit->pivot->quantite }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="prix_unitaire{{ $produit->id }}" class="form-label">Prix Unitaire (DH)</label>
                        <input type="number" step="0.01" class="form-control" id="prix_unitaire{{ $produit->id }}" name="prix_unitaire" value="{{ $produit->pivot->prix_unitaire }}" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
