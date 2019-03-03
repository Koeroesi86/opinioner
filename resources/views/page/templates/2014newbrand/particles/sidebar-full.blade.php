@if (is_iterable($relatedPosts))
	@foreach($relatedPosts as $relatedPost)
		@if ($relatedPost->position == 'sidebar-full')
			<div class="sidebarbox">
				{!! $relatedPost->body !!}
			</div>
		@endif
	@endforeach
@endif
