@extends('layout.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Tableau de Bord</h3>
        <span class="text-muted">{{ now()->format('d/m/Y') }}</span>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <!-- Chiffre d'affaire -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Chiffre d'Affaire</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($chiffreAffaire, 2) }} DH</h3>
                        </div>
                        <i class="bi bi-wallet2 fs-1 opacity-50"></i>
                    </div>
                    <small class="opacity-75">Commandes validées/livrées</small>
                </div>
            </div>
        </div>

        <!-- Commandes -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Total Commandes</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $nombreCommandes }}</h3>
                        </div>
                        <i class="bi bi-cart3 fs-1 text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Produits / Stock</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $nombreProduits }} / {{ $quantiteStock }}</h3>
                        </div>
                        <i class="bi bi-box-seam fs-1 text-success opacity-50"></i>
                    </div>
                    <small class="text-muted">Unités en stock</small>
                </div>
            </div>
        </div>

        <!-- Clients -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted mb-1">Clients</h6>
                            <h3 class="fw-bold mb-0 text-dark">{{ $nombreClients }}</h3>
                        </div>
                        <i class="bi bi-people fs-1 text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chart -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">État des Commandes</h5>
                </div>
                <div class="card-body">
                    <canvas id="ordersChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-danger">Alertes Stock Faible</h5>
                    <a href="{{ route('produits.index') }}" class="btn btn-sm btn-outline-secondary">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($lowStockProducts as $produit)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">{{ $produit->nom }}</span>
                                    <br>
                                    <small class="text-muted">Prix: {{ $produit->prix }} DH</small>
                                </div>
                                <span class="badge bg-danger rounded-pill">{{ $produit->stock }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-4">Aucun produit en rupture.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Commandes Récentes</h5>
            <a href="{{ route('commandes.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#ID</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $commande)
                            <tr>
                                <td class="fw-bold">#{{ $commande->id }}</td>
                                <td>{{ $commande->client->nom }} {{ $commande->client->prenom }}</td>
                                <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                <td>{{ number_format($commande->montant_total, 2) }} DH</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $commande->statut == 'brouillon' ? 'secondary' : 
                                        ($commande->statut == 'confirmee' ? 'primary' : 
                                        ($commande->statut == 'livree' ? 'success' : 
                                        ($commande->statut == 'annulee' ? 'danger' : 
                                        ($commande->statut == 'en_cours' ? 'info' : 'dark')))) 
                                    }}">
                                        {{ ucfirst($commande->statut) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Aucune commande récente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ordersChart').getContext('2d');
        const data = @json($chartData);
        
        const labels = Object.keys(data).map(s => s.charAt(0).toUpperCase() + s.slice(1));
        const values = Object.values(data);
        
        // Colors mapping based on status logic
        const backgroundColors = [
            '#6c757d', // brouillon - secondary
            '#0d6efd', // confirmee - primary
            '#0dcaf0', // en_cours - info
            '#198754', // livree - success
            '#212529', // cloturee - dark
            '#dc3545', // annulee - danger
            '#ffc107'  // archivee - warning
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de Commandes',
                    data: values,
                    backgroundColor: backgroundColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
</script>
@endsection
