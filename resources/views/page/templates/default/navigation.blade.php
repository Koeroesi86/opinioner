
@if(isset($links) && count($links) > 0)
<ul>
	@foreach($links as $link)
		<li>
			<a href="{{ $link->url }}" target="{{ $link->target }}">{{ $link->text }}</a>
			@if(isset($link->items) && count($link->items) > 0)
			    @include("page.templates.{$template}.navigation", array('links' => $link->items))
			@endif
		</li>
	@endforeach
</ul>
@else
Navigation place
@endif
