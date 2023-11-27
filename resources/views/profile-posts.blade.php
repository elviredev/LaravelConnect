<x-layout>
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{ $avatar }}" /> {{ $username }}
            <!-- User authentifié -->
            @auth
                <!-- Si utilisateur non déja suivi et si différent de user connecté -->
                @if(!$currentlyFollowing AND auth()->user()->username != $username)
                    <form class="ml-2 d-inline" action="/create-follow/{{ $username }}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-sm">Suivre <i class="fas fa-user-plus"></i></button>
                    </form>
                @endif
                <!-- Si utilisateur déja suivi par l'utilisateur connecté -->
                @if($currentlyFollowing)
                    <form class="ml-2 d-inline" action="/remove-follow/{{ $username }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Stop Suivi <i class="fas fa-user-times"></i></button>

                    </form>
                @endif
                <!-- Si User authentifié = profil user -->
                @if(auth()->user()->username == $username)
                    <a href="/manage-avatar" class="btn btn-secondary btn-sm">Gérer Avatar</a>
                @endif
            @endauth
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="#" class="profile-nav-link nav-item nav-link color-primary active">Articles: {{ $postCount }}</a>
            <a href="#" class="profile-nav-link nav-item nav-link color-primary">Abonnés: 3</a>
            <a href="#" class="profile-nav-link nav-item nav-link color-primary">Abonnements: 2</a>
        </div>

        <div class="list-group">
            @foreach($posts as $post)
                <a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
                    <img class="avatar-tiny" src="{{ $post->user->avatar }}" />
                    <strong>{{ $post->title }}</strong> le {{ $post->created_at->format('j/n/Y') }}
                </a>
            @endforeach
        </div>
    </div>
</x-layout>
