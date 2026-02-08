<div class="modal fade" id="editProduitModal{{ $produit->id }}" tabindex="-1" aria-labelledby="editProduitModalLabel{{ $produit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProduitModalLabel{{ $produit->id }}">Modifier le Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{ route('produits.update', $produit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nom{{ $produit->id }}" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom{{ $produit->id }}" name="nom" value="{{ $produit->nom }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description{{ $produit->id }}" class="form-label">Description</label>
                        <textarea class="form-control" id="description{{ $produit->id }}" name="description" rows="2">{{ $produit->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="prix{{ $produit->id }}" class="form-label">Prix (DH)</label>
                            <input type="number" step="0.01" class="form-control" id="prix{{ $produit->id }}" name="prix" value="{{ $produit->prix }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock{{ $produit->id }}" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock{{ $produit->id }}" name="stock" value="{{ $produit->stock }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="categorie_id{{ $produit->id }}" class="form-label">Catégorie</label>
                        <select class="form-select" id="categorie_id{{ $produit->id }}" name="categorie_id" required>
                            <option value="">Sélectionner une catégorie</option>
                            @foreach(\App\Models\Categorie::all() as $categorie)
                                <option value="{{ $categorie->id }}" {{ $produit->categorie_id == $categorie->id ? 'selected' : '' }}>
                                    {{ $categorie->intitule }}
                                </option>
                            @endforeach
                        </select>
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
