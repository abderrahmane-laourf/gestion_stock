<div class="modal fade" id="showProduitModal{{ $produit->id }}" tabindex="-1" aria-labelledby="showProduitModalLabel{{ $produit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showProduitModalLabel{{ $produit->id }}">Détails du Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="fw-bold">Nom :</label>
                    <p class="text-muted">{{ $produit->nom }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Description :</label>
                    <p class="text-muted">
                        {{ $produit->description ? $produit->description : 'Aucune description disponible.' }}
                    </p>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Prix :</label>
                        <p class="text-muted">{{ number_format($produit->prix, 2) }} DH</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Stock :</label>
                        <p class="text-muted">{{ $produit->stock }} unités</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Catégorie :</label>
                    <p class="text-muted">{{ $produit->categorie->intitule ?? 'N/A' }}</p>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
