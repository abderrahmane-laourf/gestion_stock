<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Gestion Stock</title>
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
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 28px;
        }
        .btn-primary { border-radius: 8px; padding: 10px; font-weight: 600; }
        .form-control { border-radius: 8px; padding: 10px 14px; }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.15); }
    </style>
</head>
<body>

<div class="auth-card">
    <h4 class="text-center fw-bold mb-1">Mot de passe oublié ?</h4>
    <p class="text-center text-muted small mb-4">
        Entrez votre email et nous vous enverrons un lien de réinitialisation.
    </p>

    {{-- Status message (success) --}}
    @if(session('status'))
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
        </div>
    @endif

    {{-- Errors --}}
    @if($errors->any())
        <div class="alert alert-danger d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <ul class="mb-0 ps-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label fw-semibold">Adresse Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email"
                       id="email"
                       name="email"
                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="ex: admin@mail.com"
                       required autofocus>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send me-2"></i> Envoyer le lien
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-arrow-left me-1"></i> Retour à la connexion
            </a>
        </div>
    </form>
</div>

</body>
</html>
