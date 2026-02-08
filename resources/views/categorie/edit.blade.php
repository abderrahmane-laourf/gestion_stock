<div class="modal fade" id="editCategorieModal{{ $categorie->id }}" tabindex="-1" aria-labelledby="editCategorieModalLabel{{ $categorie->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategorieModalLabel{{ $categorie->id }}">Modifier la Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{ route('categories.update', $categorie->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="intitule{{ $categorie->id }}" class="form-label">Intitulé</label>
                        <input type="text" class="form-control" id="intitule{{ $categorie->id }}" name="intitule" value="{{ $categorie->intitule }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description{{ $categorie->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="description{{ $categorie->id }}" name="description" rows="3">{{ $categorie->description }}</textarea>
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
