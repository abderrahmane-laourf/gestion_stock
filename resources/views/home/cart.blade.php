@extends('layout.app')

@section('title', 'Mon Panier')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-cart3 me-2"></i>Mon Panier</h2>
        <a href="{{ route('home.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Continuer mes achats
        </a>
    </div>


    @if(count($panier) > 0)
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Produit</th>
                                <th scope="col">Prix</th>
                                <th scope="col" class="text-center">Quantité</th>
                                <th scope="col">Total</th>
                                <th scope="col" class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalPanier = 0; @endphp
                            @foreach($panier as $item)
                                @php 
                                    $totalItem = $item['prix'] * $item['quantite'];
                                    $totalPanier += $totalItem;
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if(isset($item['imageURL']) && $item['imageURL'])
                                                <img src="{{ asset('storage/' . $item['imageURL']) }}" alt="{{ $item['nom'] }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded me-3 bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-image"></i>
                                                </div>
                                            @endif
                                            <span class="fw-bold">{{ $item['nom'] }}</span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['prix'], 2) }} DH</td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">{{ $item['quantite'] }}</span>
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($totalItem, 2) }} DH</td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('home.remove', $item['id']) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5 py-3">Total Général :</td>
                                <td colspan="2" class="fw-bold fs-5 text-primary py-3">{{ number_format($totalPanier, 2) }} DH</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('home.checkout') }}" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle me-2"></i> Commander
            </a>
            <form action="{{ route('home.clear') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir vider le panier ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-2"></i> Vider le panier
                </button>
            </form>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
            <h3>Votre panier est vide</h3>
            <p class="text-muted">Découvrez nos produits et commencez vos achats !</p>
            <a href="{{ route('home.index') }}" class="btn btn-primary btn-lg mt-3">
                Voir le catalogue
            </a>
        </div>
    @endif
</div>
@endsection
