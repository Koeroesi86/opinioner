@extends("page.templates.{$template}.content")

@section('content')
{!! Form::open(array('url' => 'auth/login')) !!}
<h1>Login</h1>

<!-- if there are login errors, show them here -->
<p>
    {{ $errors->first('message') }}
    {{ $errors->first('password') }}
</p>

<p>
    {!! Form::label('name', _('Name')) !!}
    {!! Form::text('name', Input::old('text'), array('placeholder' => _('John Doe'))) !!}
</p>

<p>
    {!! Form::label('email', _('Email Address')) !!}
    {!! Form::text('email', Input::old('email'), array('placeholder' => _('awesome@awesome.com'))) !!}
</p>

<p>
    {!! Form::label('password', _('Password')) !!}
    {!! Form::password('password') !!}
</p>

<p>
    {!! Form::label('confirm-password', _('Confirm Password')) !!}
    {!! Form::password('password') !!}
</p>

<p>{!! Form::submit('Submit!') !!}</p>
{!! Form::close() !!}
@endsection
