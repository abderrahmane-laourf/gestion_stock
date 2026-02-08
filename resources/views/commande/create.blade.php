<div class="modal fade" id="createCommandeModal" tabindex="-1" aria-labelledby="createCommandeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCommandeModalLabel">Nouvelle Commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('commandes.store') }}" method="POST">
                @csrf
                <div class="modal-body text-start">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label">Client</label>
                            <select class="form-select" id="client_id" name="client_id" required>
                                <option value="">Sélectionner un client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->nom }} {{ $client->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_commande" class="form-label">Date de commande</label>
                            <input type="date" class="form-control" id="date_commande" name="date_commande" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse_livraison" class="form-label">Description / Adresse</label>
                        <textarea class="form-control" id="adresse_livraison" name="adresse_livraison" rows="2" placeholder="Saisir l'adresse de livraison..." required></textarea>
                    </div>

                    <hr>
                    <h6 class="fw-bold mb-3">Sélection des Produits</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="produits-table">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 45%;">Produit</th>
                                    <th style="width: 20%;">Prix (DH)</th>
                                    <th style="width: 20%;">Quantité</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="produits-container">
                                <tr class="produit-row">
                                    <td>
                                        <select name="produits[0][produit_id]" class="form-select produit-select" required>
                                            <option value="">Choisir...</option>
                                            @foreach(\App\Models\Produit::all() as $produit)
                                                <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}">
                                                    {{ $produit->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="produits[0][prix_unitaire]" class="form-control prix-input" step="0.01" required>
                                    </td>
                                    <td>
                                        <input type="number" name="produits[0][quantite]" class="form-control" min="1" value="1" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-item-btn">
                        <i class="bi bi-plus-circle me-1"></i> Ajouter un autre produit
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer la Commande</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('produits-container');
    const addBtn = document.getElementById('add-item-btn');
    let rowIndex = 1;

    // Auto-prix quand on choisit un produit
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('produit-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const prix = selectedOption.getAttribute('data-prix');
            const row = e.target.closest('tr');
            if (prix) {
                row.querySelector('.prix-input').value = prix;
            }
        }
    });

    // Ajouter une ligne
    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.produit-row');
        const newRow = firstRow.cloneNode(true);
        
        // Update names and clear values
        newRow.querySelector('select').name = `produits[${rowIndex}][produit_id]`;
        newRow.querySelector('select').value = '';
        newRow.querySelector('.prix-input').name = `produits[${rowIndex}][prix_unitaire]`;
        newRow.querySelector('.prix-input').value = '';
        newRow.querySelector('input[type="number"]:not(.prix-input)').name = `produits[${rowIndex}][quantite]`;
        newRow.querySelector('input[type="number"]:not(.prix-input)').value = '1';
        
        container.appendChild(newRow);
        rowIndex++;
    });

    // Supprimer une ligne
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const rows = container.querySelectorAll('.produit-row');
            if (rows.length > 1) {
                e.target.closest('.produit-row').remove();
            } else {
                alert('Il faut au moins un produit pour la commande.');
            }
        }
    });
});
</script>
