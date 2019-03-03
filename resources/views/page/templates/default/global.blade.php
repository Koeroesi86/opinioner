<!DOCTYPE html>
<html>
	<head>
@section('meta')
	<title>
		@if(!empty($title))
        	{{{ $title }}}{{ $settings['system_title_separator'] }}
		@endif
		{{ $settings['system_sitename'] }}</title>
	{!! HTML::style('/templates/default/style.css') !!}
	{!! HTML::style('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css') !!}
	{!! HTML::script('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js') !!}
	{!! HTML::script('https://code.jquery.com/jquery-2.1.3.min.js') !!}
	{!! HTML::script('https://code.jquery.com/ui/1.11.4/jquery-ui.min.js') !!}
@show
	</head>
	<body>
	<header>
@section('header')
	Logo place
	@include("page.templates.{$template}.navigation")
@show
	</header>

@yield('content')

@section('footer')
	<footer>
		&copy {{ date('Y') }}
	</footer>
@show

	</body>
</html>
