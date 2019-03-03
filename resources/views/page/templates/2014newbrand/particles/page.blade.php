@extends("page.templates.{$template}.content")

@section('page-content')
    <div id="main">
      @foreach($global->relatedPosts as $relatedPost)
      	@if ($relatedPost->position == 'sidebar-full')
        	<div class="sidebarbox">
            	{!! $relatedPost->body !!}
            </div>
        @endif
      @endforeach
      <!-- get_sidebar('full') -->
    </div>
    <div id="wrapper" class="page-template-page_fullwidth-php">
		<div class="topPostHeader">
			<h2 class="topTitle">
				<span>{{ $page->title }}</span>
			</h2>
		</div>
		<div class="excerpt">
		    {!! $page->excerpt !!}
		</div>
		<div id="contentwrapper2">
			<div class="topPost">
		    	<div class="topContent">
		        	{!! $page->body !!}
		        </div>
		    </div>
		    
			<div id="comment">
				@include("page.templates.2014newbrand.particles.comments", array('user' => $user, 'uri' => $uri, 'comments' => $page->comments))
			</div>
		</div>

        <a href="javascript:void(0)" class="switch-mobile"></a>
        <div class="cleared"></div>
    </div>

@stop