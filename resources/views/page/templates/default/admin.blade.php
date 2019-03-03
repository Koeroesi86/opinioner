<!DOCTYPE html>
<html>
<head>
    @section('meta')
        <meta charset="utf-8">
        <title>
                @if(env('APP_ENV') != 'production')
                        [DEV]
                @endif
                {{ _("Administration") }}</title>
        <base href="/admin/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/x-icon" href="{{{ URL::to('/os-admin/dist/favicon.ico') }}}">
        <meta property="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    @show
</head>
<body>
@section('content')
    <os-root>{{ _("Loading...") }}</os-root>
@show
@section('footer')
    {!! Html::script("/os-admin/dist/runtime.js") !!}
    {!! Html::script("/os-admin/dist/polyfills.js") !!}
    {!! Html::script("/os-admin/dist/styles.js") !!}
    {!! Html::script("/os-admin/dist/vendor.js") !!}
    {!! Html::script("/os-admin/dist/main.js") !!}
@show
</body>
</html>
