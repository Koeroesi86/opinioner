@section('comments')
	<div class="comments">
		<div class="comment-form">

		</div>
		<div class="feed">
			@foreach($comments as $comment)
				@include("page.templates.{$template}.comment", array('comment' => $comment))
			@endforeach
		</div>
	</div>
@show
