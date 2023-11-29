<x-layout>
    <div class="container py-md-5 container--narrow">
        <!-- Si la liste des articles n'est pas vide -->
        @unless($posts->isEmpty())
            <h2 class="text-center mb-4">Les dernières nouvelles de ceux que vous suivez ‍👩🏼‍💻</h2>
            <div class="list-group">
                @foreach($posts as $post)
                <x-post :post="$post" />
                @endforeach
            </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
        <!-- Si aucun article publié -->
        @else
            <div class="text-center">
                <h2>Hello <strong class="color-primary">{{ auth()->user()->username }}</strong>, votre flux est vide..</h2>
                <p class="lead text-muted">Votre flux affiche les dernières publications des personnes que vous suivez. Si vous n’avez pas d’amis à suivre, ce n’est pas grave; vous pouvez utiliser la fonction &ldquo;Rechercher&rdquo; dans la barre de menu supérieure pour trouver du contenu écrit par des personnes partageant les mêmes intérêts, puis les suivre.</p>
            </div>
        @endunless
    </div>
</x-layout>
