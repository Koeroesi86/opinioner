@extends("page.templates.{$template}.content")

@section('content')
<h1 class="topTitle"><span>{{ $page->title }}</span></h1>
<div class="excerpt">
    <p>{{ $page->excerpt }}</p>
</div>
<div id="contentwrapper">
	<div class="topPost">
    	<div class="topContent">
        	{!! $page->body !!}
        </div>
    </div>
</div>

@stop
