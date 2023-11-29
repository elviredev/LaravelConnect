<a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{ $post->user->avatar }}" />
    <strong>{{ $post->title }}</strong>
    <span class="text-muted small">
        @if(!isset($hideAuthor))
            par {{ $post->user->username }}
        @endif
        le {{ $post->created_at->format('j/n/Y') }}
    </span>
</a>
