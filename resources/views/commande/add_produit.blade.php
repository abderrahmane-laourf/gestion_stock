<div class="modal fade" id="addProduitModal" tabindex="-1" aria-labelledby="addProduitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProduitModalLabel">Ajouter un Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{ route('commandes.addProduit', $commande->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="produit_id" class="form-label">Produit</label>
                        <select class="form-select" id="produit_id" name="produit_id" required>
                            <option value="">Sélectionner un produit</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}">
                                    {{ $produit->nom }} - {{ number_format($produit->prix, 2) }} DH
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité</label>
                        <input type="number" class="form-control" id="quantite" name="quantite" min="1" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="prix_unitaire" class="form-label">Prix Unitaire (DH)</label>
                        <input type="number" step="0.01" class="form-control" id="prix_unitaire" name="prix_unitaire" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('produit_id')?.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const prix = selectedOption.getAttribute('data-prix');
    if (prix) {
        document.getElementById('prix_unitaire').value = prix;
    }
});
</script>
