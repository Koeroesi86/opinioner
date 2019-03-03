@extends("page.templates.{$template}.content")

@section('page-content')
<div id="main">
  @include("page.templates.{$template}.particles.sidebar-full", array('relatedPosts' => $global->relatedPosts))
</div>
<div id="wrapper" class="page-template-page_fullwidth-php">
	<div class="comment-page">
		<div class="topPostHeader">
			<h2 class="topTitle">
				<span>{{ $page->title }}</span>
			</h2>
			<p class="meta">
				<a href="{{ URL::to($page->uri) }}" class="created">[{{ $page->created_at }}]</a>
				@if (count($page->parentPosts) > 0)
					{{ _("sent to: ") }}
					@foreach ($page->parentPosts as $parent)
						<a href="{{ URL::to($parent->uri) }}" class="parent">{{ $parent->title }}</a>
					@endforeach
				@endif
			</p>
		</div>
		<div class="content">{{ $page->body }}</div>
		
	</div>

	<a href="javascript:void(0)" class="switch-mobile"></a>
    <div class="cleared"></div>
</div>
@stop