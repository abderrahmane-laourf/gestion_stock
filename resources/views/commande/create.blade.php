@extends('layout.app')

@section('title', 'Nouvelle Commande')

@section('content')
<div class="container-fluid">
    {{-- En-tête de page --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Nouvelle Commande</h3>
        <a href="{{ route('commandes.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('commandes.store') }}" method="POST">
                @csrf

                {{-- Informations générales --}}
                <div class="row mb-3">
                    <div class="col-md-4 mb-3">
                        <label for="client_id" class="form-label fw-bold">Client</label>
                        <select class="form-select" id="client_id" name="client_id" required>
                            <option value="">Sélectionner un client</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->nom }} {{ $client->prenom }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="date_commande" class="form-label fw-bold">Date de commande</label>
                        <input type="date" class="form-control" id="date_commande" name="date_commande"
                               value="{{ old('date_commande', date('Y-m-d')) }}" required>
                        @error('date_commande')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="adresse_livraison" class="form-label fw-bold">Adresse de livraison</label>
                        <textarea class="form-control" id="adresse_livraison" name="adresse_livraison" rows="1"
                                  placeholder="Saisir l'adresse..." required>{{ old('adresse_livraison') }}</textarea>
                        @error('adresse_livraison')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr>

                {{-- Sélection des produits --}}
                <h5 class="fw-bold mb-3"><i class="bi bi-box-seam me-1"></i> Sélection des Produits</h5>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="produits-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%;">Produit</th>
                                <th style="width: 20%;">Prix Unitaire (DH)</th>
                                <th style="width: 20%;">Quantité</th>
                                <th style="width: 20%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="produits-container">
                            <tr class="produit-row">
                                <td>
                                    <select name="produits[0][produit_id]" class="form-select produit-select" required>
                                        <option value="">Choisir un produit...</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" data-prix="{{ $produit->prix }}">
                                                {{ $produit->nom }} (Stock: {{ $produit->stock }})
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
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-row">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-sm btn-outline-primary mb-4" id="add-item-btn">
                    <i class="bi bi-plus-circle me-1"></i> Ajouter un autre produit
                </button>

                {{-- Boutons de soumission --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer la Commande
                    </button>
                    <a href="{{ route('commandes.index') }}" class="btn btn-secondary">Annuler</a>
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

    // Remplir automatiquement le prix quand on choisit un produit
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

    // Ajouter une nouvelle ligne de produit
    addBtn.addEventListener('click', function() {
        const firstRow = container.querySelector('.produit-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelector('select').name = `produits[${rowIndex}][produit_id]`;
        newRow.querySelector('select').value = '';
        newRow.querySelector('.prix-input').name = `produits[${rowIndex}][prix_unitaire]`;
        newRow.querySelector('.prix-input').value = '';
        newRow.querySelector('input[type="number"]:not(.prix-input)').name = `produits[${rowIndex}][quantite]`;
        newRow.querySelector('input[type="number"]:not(.prix-input)').value = '1';

        container.appendChild(newRow);
        rowIndex++;
    });

    // Supprimer une ligne de produit
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            const rows = container.querySelectorAll('.produit-row');
            if (rows.length > 1) {
                e.target.closest('.produit-row').remove();
            } else {
                alert('Il faut au moins un produit.');
            }
        }
    });
});
</script>
@endsection
