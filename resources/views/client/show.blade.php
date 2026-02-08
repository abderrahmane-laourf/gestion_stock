
<div class="modal fade" id="showClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="showClientModalLabel{{ $client->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showClientModalLabel{{ $client->id }}">Afficher le client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
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
            </div>
        </div>
    </div>
</div>