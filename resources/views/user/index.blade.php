@extends('layout.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">
            <i class="bi bi-people-fill me-2"></i> Utilisateurs
        </h3>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Nouvel Utilisateur
        </a>
    </div>

    {{-- Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Catégorie</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}"
                                         alt="{{ $user->name }}"
                                         class="rounded-circle"
                                         style="width:38px;height:38px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:38px;height:38px;font-size:14px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td><code>{{ $user->username }}</code></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $roleColors = [
                                    'admin'       => 'danger',
                                    'magasinier'  => 'warning',
                                    'commercial'  => 'success',
                                    'livreur'     => 'info',
                                ];
                                $color = $roleColors[$user->role] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} text-capitalize">
                                {{ $user->role ?? '—' }}
                            </span>
                        </td>
                        <td>{{ $user->category }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('users.show', $user->id) }}"
                                   class="btn btn-sm btn-outline-info" title="Voir">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="btn btn-sm btn-outline-warning" title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-danger" title="Supprimer"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Supprimer l'utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            Êtes-vous sûr de vouloir supprimer
                                            <strong>{{ $user->name }}</strong> ?
                                            <p class="text-muted small mt-1">Cette action est irréversible.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-person-x fs-3 d-block mb-2"></i>
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
