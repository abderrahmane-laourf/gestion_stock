<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Gestion Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f2f5;
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            padding: 40px 36px;
        }
        .auth-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #198754, #146c43);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 28px;
        }
        .btn { border-radius: 8px; padding: 10px; font-weight: 600; }
        .form-control { border-radius: 8px; padding: 10px 14px; }
        .form-control:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.15); }
        .toggle-pw { cursor: pointer; }
    </style>
</head>
<body>

<div class="auth-card">
    <h4 class="text-center fw-bold mb-1">Nouveau mot de passe</h4>
    <p class="text-center text-muted small mb-4">
        Choisissez un nouveau mot de passe sécurisé.
    </p>

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill mt-1"></i>
            <ul class="mb-0 ps-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf

        {{-- Hidden token --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Adresse Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                       value="{{ old('email', request()->email) }}"
                       placeholder="Votre email"
                       required autofocus>
            </div>
            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- New Password --}}
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Nouveau mot de passe</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-muted"></i>
                </span>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control border-start-0 border-end-0 ps-0 @error('password') is-invalid @enderror"
                       placeholder="Minimum 6 caractères"
                       required>
                <span class="input-group-text bg-light toggle-pw" onclick="togglePw('password', this)">
                    <i class="bi bi-eye text-muted"></i>
                </span>
            </div>
            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Confirmer le mot de passe</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock-fill text-muted"></i>
                </span>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       class="form-control border-start-0 border-end-0 ps-0"
                       placeholder="Répéter le mot de passe"
                       required>
                <span class="input-group-text bg-light toggle-pw" onclick="togglePw('password_confirmation', this)">
                    <i class="bi bi-eye text-muted"></i>
                </span>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle me-2"></i> Réinitialiser le mot de passe
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Retour à la connexion
            </a>
        </div>
    </form>
</div>

<script>
function togglePw(id, el) {
    const input = document.getElementById(id);
    const icon  = el.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
</body>
</html>
