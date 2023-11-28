<x-layout>
    <div class="container py-md-5 container--narrow">
        {{-- Heading : Avatar, username, boutons de suivi --}}
        <h2>
            <img class="avatar-small" src="{{ $sharedData['avatar'] }}" /> {{ $sharedData['username'] }}
            <!-- User authentifié -->
            @auth
                <!-- Si utilisateur non déja suivi et si différent de user connecté -->
                @if(!$sharedData['currentlyFollowing'] AND auth()->user()->username != $sharedData['username'])
                    <form class="ml-2 d-inline" action="/create-follow/{{ $sharedData['username'] }}" method="POST">
                        @csrf
                        <button class="btn btn-primary btn-sm">Suivre <i class="fas fa-user-plus"></i></button>
                    </form>
                @endif
                <!-- Si utilisateur déja suivi par l'utilisateur connecté -->
                @if($sharedData['currentlyFollowing'])
                    <form class="ml-2 d-inline" action="/remove-follow/{{ $sharedData['username'] }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Stop Suivi <i class="fas fa-user-times"></i></button>

                    </form>
                @endif
                <!-- Si User authentifié = profil user -->
                @if(auth()->user()->username == $sharedData['username'])
                    <a href="/manage-avatar" class="btn btn-secondary btn-sm">Gérer Avatar</a>
                @endif
            @endauth
        </h2>

        {{-- Onglets --}}
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a
                href="/profile/{{ $sharedData['username'] }}"
                class="profile-nav-link nav-item nav-link color-primary {{ Request::segment(3) == "" ? "active" : "" }}">
                Articles: {{ $sharedData['postCount'] }}
            </a>
            <a
                href="/profile/{{ $sharedData['username'] }}/followers"
                class="profile-nav-link nav-item nav-link color-primary {{ Request::segment(3) == "followers" ? "active" : "" }}">
                Abonnés: {{ $sharedData['followerCount'] }}
            </a>
            <a
                href="/profile/{{ $sharedData['username'] }}/following"
                class="profile-nav-link nav-item nav-link color-primary {{ Request::segment(3) == "following" ? "active" : "" }}">
                Abonnements: {{ $sharedData['followingCount'] }}
            </a>
        </div>

        {{-- Contenu des pages Articles/Abonnés/Abonnements --}}
        <div class="profile-slot-content">
            {{ $slot }}
        </div>
    </div>
</x-layout>
