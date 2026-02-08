<div class="modal fade" id="showCategorieModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="showCategorieModalLabel{{ $categorie->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCategorieModalLabel{{ $categorie->id }}">Détails de la Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <div class="mb-3">
                    <label class="fw-bold">Intitulé :</label>
                    <p class="text-muted">{{ $categorie->intitule }}</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold">Description :</label>
                    <p class="text-muted">
                        {{ $categorie->description ? $categorie->description : 'Aucune description disponible.' }}
                    </p>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
</div>
