@extends('layout.app')

@section('title', 'Validation de la Commande')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center fw-bold"><i class="bi bi-person-check me-2"></i>Informations du Client</h2>
            
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('home.store_order') }}" method="POST">
                        @csrf
                        
                        <h5 class="mb-3 text-primary">Vos Coordonnées</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tele" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="tele" name="tele" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ville" name="ville" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="3" required></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Votre commande contient <strong>{{ count($panier) }}</strong> produit(s) pour un total de 
                            <strong>{{ number_format(array_reduce($panier, function($carry, $item) { return $carry + ($item['prix'] * $item['quantite']); }, 0), 2) }} DH</strong>.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('home.cart') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Retour au Panier
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-lg me-2"></i> Confirmer la Commande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
