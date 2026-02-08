<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande #{{ $commande->id }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .header { text-align: center; margin-bottom: 40px; }
        .info { margin-bottom: 20px; }
        .info div { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-size: 1.2em; font-weight: bold; }
        .footer { margin-top: 50px; text-align: center; font-size: 0.8em; color: #666; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()">Imprimer</button>
        <button onclick="window.close()">Fermer</button>
    </div>

    <div class="header">
        <h1>Bon de Commande #{{ $commande->id }}</h1>
        <p>Date: {{ $commande->date_commande->format('d/m/Y') }}</p>
    </div>

    <div class="info">
        <h3>Client</h3>
        <div><strong>Nom:</strong> {{ $commande->client->nom }} {{ $commande->client->prenom }}</div>
        <div><strong>Adresse de livraison:</strong> {{ $commande->adresse_livraison }}</div>
        <div><strong>Statut:</strong> {{ ucfirst($commande->statut) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commande->produits as $produit)
            <tr>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->pivot->quantite }}</td>
                <td>{{ number_format($produit->pivot->prix_unitaire, 2) }} DH</td>
                <td>{{ number_format($produit->pivot->quantite * $produit->pivot->prix_unitaire, 2) }} DH</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: {{ number_format($commande->montant_total, 2) }} DH
    </div>

    <div class="footer">
        <p>Merci de votre confiance.</p>
    </div>

    <script>
        // Auto print if requested via query param
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('mode') === 'pdf') {
            window.print();
        }
    </script>
</body>
</html>
