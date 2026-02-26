<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion Stock</title>
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
        .auth-logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 28px;
        }
        .btn-primary { border-radius: 8px; padding: 10px; font-weight: 600; letter-spacing: .3px; }
        .form-control { border-radius: 8px; padding: 10px 14px; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.15); }
        .input-group-text { border-radius: 8px 0 0 8px; }
        .toggle-pw { cursor: pointer; border-radius: 0 8px 8px 0 !important; }
    </style>
</head>
<body>

<div class="auth-card">

    <h4 class="text-center fw-bold mb-1">Stock.ma</h4>
    <p class="text-center text-muted small mb-4">Connectez-vous à votre espace</p>

    {{-- Status (reset success) --}}
    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2 py-2">
            <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        {{-- Username --}}
        <div class="mb-3">
            <label for="username" class="form-label fw-semibold">Nom d'utilisateur</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-person text-muted"></i>
                </span>
                <input type="text"
                       id="username"
                       name="username"
                       class="form-control border-start-0 ps-0 @error('username') is-invalid @enderror"
                       value="{{ old('username') }}"
                       placeholder="ex: admin"
                       required autofocus>
            </div>
        </div>

        {{-- Password --}}
        <div class="mb-2">
            <label for="password" class="form-label fw-semibold">Mot de passe</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-muted"></i>
                </span>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control border-start-0 border-end-0 ps-0"
                       placeholder="Votre mot de passe"
                       required>
                <span class="input-group-text bg-light toggle-pw" onclick="togglePw()">
                    <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                </span>
            </div>
        </div>

        {{-- Forgot password link --}}
        <div class="text-end mb-4">
            <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">
                Mot de passe oublié ?
            </a>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-2"></i> Se connecter
            </button>
        </div>
    </form>
</div>

<script>
function togglePw() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eyeIcon');
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
