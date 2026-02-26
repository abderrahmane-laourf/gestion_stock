@extends('layout.app')

@section('title', 'Modifier l\'Utilisateur')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-warning">
            <i class="bi bi-pencil-square me-2"></i> Modifier : {{ $user->name }}
        </h3>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>
    </div>

    <div class="card shadow-sm border-0" style="max-width:680px;">
        <div class="card-body p-4">

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Avatar preview --}}
                <div class="text-center mb-4">
                    @if($user->image)
                        <img id="avatarPreview"
                             src="{{ asset('storage/' . $user->image) }}"
                             class="rounded-circle border border-3 border-warning"
                             style="width:100px;height:100px;object-fit:cover;">
                    @else
                        <img id="avatarPreview"
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6c757d&color=fff&size=100"
                             class="rounded-circle border border-3 border-warning"
                             style="width:100px;height:100px;object-fit:cover;">
                    @endif
                    <div class="mt-2">
                        <label for="image" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-camera me-1"></i> Changer photo
                        </label>
                        <input type="file" id="image" name="image" class="d-none" accept="image/*"
                               onchange="previewImage(this)">
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Username --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}" required>
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Role --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rôle <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @foreach($roles as $role)
                                <option value="{{ $role }}"
                                    {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Category --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Catégorie</label>
                        <input type="text" name="category" class="form-control"
                               value="{{ old('category', $user->category) }}">
                    </div>

                    {{-- Divider --}}
                    <div class="col-12"><hr class="my-1"><small class="text-muted">Laisser vide pour conserver le mot de passe actuel.</small></div>

                    {{-- Password --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nouveau mot de passe</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               placeholder="Laisser vide pour ne pas changer">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Confirmer le nouveau mot de passe">
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle me-1"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
