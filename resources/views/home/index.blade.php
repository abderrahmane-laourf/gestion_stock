@extends('layout.app')

@section('title', 'Catalogue des Produits')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold text-primary">Catalogue des Produits</h2>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach($produits as $produit)
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="position-relative" style="height: 200px; background-color: #f8f9fa;">
                        @if($produit->imageURL)
                            <img src="{{ asset('storage/' . $produit->imageURL) }}" class="card-img-top w-100 h-100" style="object-fit: cover;" alt="{{ $produit->nom }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                <i class="bi bi-image fs-1"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-dark">{{ $produit->nom }}</h5>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($produit->description, 60) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-end mt-3">
                            <div>
                                <span class="h5 fw-bold text-primary">{{ number_format($produit->prix, 2) }} <small>DH</small></span>
                            </div>
                            <form action="{{ route('home.add', $produit->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary rounded-circle p-2 shadow-sm" title="Ajouter au panier">
                                    <i class="bi bi-cart-plus fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    @if($produits->isEmpty())
        <div class="text-center py-5">
            <p class="text-muted fs-4">Aucun produit disponible pour le moment.</p>
        </div>
    @endif
</div>
@endsection
