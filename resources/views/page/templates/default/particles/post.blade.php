@extends("page.templates.{$template}.content")

@section('content')

<div class="postImage" title="{{ $page->title }}">
  <img src="{{ URL::to($page->featuredImage) }}" class="singleFeatured">
  <div class="slideInfoZone">
    <h2 class="topTitle"><span>{{ $page->title }}</span></h2>
    <div class="excerpt">
        <p>{{ $page->excerpt }}</p>
    </div>
  </div>
</div>
<div id="contentwrapper">
	<div class="topPost">
    	<div class="topContent">
        	{!! $page->body !!}
        </div>
    </div>
</div>

@stop
