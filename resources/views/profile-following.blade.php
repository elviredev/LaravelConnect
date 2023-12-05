<x-profile
    :sharedData="$sharedData"
    docTitle="Abonnements de {{ $sharedData['username'] }}"
>
    @include('profile-following-only')
</x-profile>
