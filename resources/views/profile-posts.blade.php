<x-profile
    :sharedData="$sharedData"
    docTitle="Profil de {{ $sharedData['username'] }}"
>
    @include('profile-posts-only')
</x-profile>
