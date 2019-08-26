@php
    if (!isset($current)) {
        $current = (new stdClass);
        $current->discussion_id = null;
    }
@endphp

<blockquote class="shadow-sm">
    <div class="flex justify-between">
        <div>
            <a href="{{ $post->user->link }}"><strong>{{ $post->user->display_name }}</strong></a>
            @if ($post->user->display_name != $post->user->name)
                <small>{{ '@' . $post->user->name }}</small>
            @endif
        </div>
        <div>
            @if ($post->discussion_id !== $current->discussion_id)
                <a href="{{ $post->discussion->link }}" target="_blank" title="Voir le topic" class="text-small mr-2">
                    {{ $post->discussion->title }}
                </a>
            @endif

            <a href="{{ $post->link }}" title="Voir le contexte" class="text-small">
                <i class="fas fa-link"></i>
            </a>
        </div>
    </div>
    <hr class="mt-2 mb-3">
    <div class="quote-content post-content">
        @if ($post->deleted_at)
            <div class="text-danger mb-3"><i class="fas fa-times"></i> Message supprimé</div>
        @endif
        @if (!$post->deleted_at || auth()->check() && user()->can('read deleted posts'))
            @if ($post->deleted_at) <div class="deleted-message-content text-italic text-muted"> @endif
                {!! $post->presented_light_body !!}
            @if ($post->deleted_at) </div> @endif
        @endif
    </div>
</blockquote>