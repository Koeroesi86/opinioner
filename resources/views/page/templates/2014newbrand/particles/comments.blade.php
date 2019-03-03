@section('comments')
	<div class="comments">
		<div class="comment-form">
			@include("page.templates.{$template}.particles.comment-form", array('uri' => $uri, 'user' => $user))
		</div>
		<div class="feed">
			@foreach($comments as $comment)
				<div class="comment">
					<div class="author">
						{{ $comment->author->name }}
					</div>
					<div class="meta">
						<a href="{{ URL::to($comment->uri) }}">{{ $comment->created_at }}</a>
					</div>
					{{ $comment->body }}
				</div>
			@endforeach
		</div>
	</div>
@show