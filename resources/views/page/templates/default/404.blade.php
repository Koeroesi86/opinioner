@extends("page.templates.{$template}.global")

@section('header')
	@parent

@stop

@section('content')
<div class="wrapper">
	<p>{{ _("Unfortunately the page you've specified does not exists:") }} <pre title="{{ $uri }}">{{{ URL::to($uri) }}}</pre></p>
</div>
@stop
