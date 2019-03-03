@extends("page.templates.{$template}.content")

@section('page-content')
    <div id="main">
      <!-- get_sidebar('full') -->
    </div>
    <div id="wrapper" class="page-template-page_fullwidth-php">
        <div class="topPostHeader">
            <h2 class="topTitle">
                <span>{{ _("Register") }}</span>
            </h2>
        </div>

        <div id="contentwrapper2">
            {!! Form::open(array('url' => 'auth/register', 'class'=> 'fullpage register')) !!}
                
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
                    {!! Form::label('password_confirmation', _('Confirm Password')) !!}
                    {!! Form::password('password_confirmation') !!}
                </p>
                
                <p>{!! Form::submit(_('Submit'), array('class' => 'button-primary')) !!}</p>
                <ul class="register-links">
                    <li>
                        <a href="{{ URL::to("auth/login") }}">{{ _("Login") }}</a>
                    </li>
                    <li>
                        <a href="{{ URL::to("auth/password-reminder") }}">{{ _("Forgotten password") }}</a>
                    </li>
                </ul>
            {!! Form::close() !!}

            <a href="javascript:void(0)" class="switch-mobile"></a>
            <div class="cleared"></div>
        </div>
    </div>

@endsection
