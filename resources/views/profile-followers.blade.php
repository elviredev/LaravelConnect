<x-profile
    :sharedData="$sharedData"
    docTitle="Abonnés de {{ $sharedData['username'] }}"
>
    @include('profile-followers-only')
</x-profile>
