<x-layout docTitle="Créer un article">
    <div class="container py-md-5 container--narrow">
        <form action="/create-post" method="POST">
            @csrf
            <div class="form-group">
                <label for="post-title" class="text-muted mb-1"><small>Titre</small></label>
                <input value="{{ old('title') }}" name="title" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
                @error('title')
                    <p class="m-0 small alert alert-danger-pink shadow-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="post-body" class="text-muted mb-1"><small>Contenu</small></label>
                <textarea name="body" id="post-body" class="body-content tall-textarea form-control" type="text">{{ old('body') }}</textarea>
                @error('body')
                <p class="m-0 small alert alert-danger-pink shadow-sm">{{ $message }}</p>
                @enderror
            </div>

            <button class="btn btn-primary">Enregistrer l'article</button>
        </form>
    </div>
</x-layout>
