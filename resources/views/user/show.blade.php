@extends('layout.app')

@section('title', 'Profil Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-info">
            <i class="bi bi-person-badge me-2"></i> Profil Utilisateur
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square me-1"></i> Modifier
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center p-4">
                @if($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}"
                         class="rounded-circle mx-auto border border-3 border-primary mb-3"
                         style="width:120px;height:120px;object-fit:cover;">
                @else
                    <div class="rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center text-white fw-bold mb-3"
                         style="width:120px;height:120px;font-size:48px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <p class="text-muted mb-2"><code>@{{ $user->username }}</code></p>

                @php
                    $roleColors = ['admin'=>'danger','magasinier'=>'warning','commercial'=>'success','livreur'=>'info'];
                    $color = $roleColors[$user->role] ?? 'secondary';
                @endphp
                <span class="badge bg-{{ $color }} fs-6 px-3 py-2 mb-3">{{ ucfirst($user->role) }}</span>

                {{-- Quick image update --}}
                <form action="{{ route('users.updateImage', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="quickImage" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="bi bi-camera me-1"></i> Changer la photo
                    </label>
                    <input type="file" id="quickImage" name="image" class="d-none" accept="image/*"
                           onchange="this.form.submit()">
                </form>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Informations</h5>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted text-uppercase fw-semibold">Nom complet</small>
                        <p class="fw-semibold mb-0">{{ $user->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted text-uppercase fw-semibold">Username</small>
                        <p class="fw-semibold mb-0"><code>{{ $user->username }}</code></p>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted text-uppercase fw-semibold">Email</small>
                        <p class="fw-semibold mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted text-uppercase fw-semibold">Catégorie</small>
                        <p class="fw-semibold mb-0">{{ $user->category }}</p>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted text-uppercase fw-semibold">Créé le</small>
                        <p class="fw-semibold mb-0">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted text-uppercase fw-semibold">Dernière mise à jour</small>
                        <p class="fw-semibold mb-0">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                {{-- Quick Role Change --}}
                <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2">Changer le Rôle</h5>
                <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <select name="role" class="form-select">
                        @foreach(['admin','magasinier','commercial','livreur'] as $role)
                            <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                {{ ucfirst($role) }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary text-nowrap">
                        <i class="bi bi-shield-check me-1"></i> Appliquer
                    </button>
                </form>

                {{-- Danger zone --}}
                <div class="mt-4 pt-3 border-top">
                    <button class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                        <i class="bi bi-trash me-1"></i> Supprimer cet utilisateur
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Supprimer l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir supprimer <strong>{{ $user->name }}</strong> ?
                <p class="text-muted small mt-1">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
