<!-- create a modal for editer ok ?  -->
<div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editClientModalLabel{{ $client->id }}">Modifier le client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form action="{{ route('clients.update', $client->id)}}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nom{{ $client->id }}" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom{{ $client->id }}" name="nom" value="{{ $client->nom }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom{{ $client->id }}" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom{{ $client->id }}" name="prenom" value="{{ $client->prenom }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="telephone{{ $client->id }}" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="telephone{{ $client->id }}" name="telephone" value="{{ $client->telephone }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse{{ $client->id }}" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse{{ $client->id }}" name="adresse" value="{{ $client->adresse }}" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </form>
            </div>
        </div>
    </div>
</div>