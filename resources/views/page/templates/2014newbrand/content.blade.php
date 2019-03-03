@extends("page.templates.{$template}.global")

@section('header')
	@parent
@stop


@section('content')
<div id="wrapperBackground">
    <div id="top"></div>
    @yield('page-content')
    <div class="footerBeforePlace">&nbsp;</div>
</div>
@stop
