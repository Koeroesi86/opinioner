@extends("page.templates.{$template}.global")

@section('header')
	@parent

	{{ _("Site") }}
	<nav>
		@include("page.templates.{$template}.navigation")
	</nav>
@stop

@section('content')
{!! $page->body or '' !!}
@stop
