<li>
	<a href="{{ $link->url }}" target="{{ $link->target }}">{{ $link->text }}</a>
	@if(isset($link->items) && count($link->items) > 0)
	    @include("page.templates.{$template}.navigation", array('links' => $link->items))
	@endif
</li>