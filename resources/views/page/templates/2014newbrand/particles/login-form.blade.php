{!! Form::open(array('url' => 'auth/login', 'method' => 'POST', 'class' => 'fullpage')) !!}
    <p>
        {!! Form::label('email', _('Email address')) !!}
        {!! Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com', 'class' => 'input')) !!}
    </p>
    <p>
        {!! Form::label('password', _('Password')) !!}
        {!! Form::password('password', array('class' => 'input')) !!}
    </p>
    <p class="login-remember">
        <label>
            {!! Form::checkbox('rememberme', 'forever') !!}
            {{ _("Stay signed in") }}
        </label>
    </p>
    <p class="login-submit">
        {!! Form::submit( _('Login'), array('class'=>"button-primary") ) !!}
    </p>
    <ul class="login-links">
        <li>
            <a href="{{ URL::to("auth/password-reminder") }}">{{ _("Forgotten password") }}</a>
        </li>
        <li>
            <a href="{{ URL::to("auth/register") }}">{{ _("Register") }}</a>
        </li>
    </ul>
{!! Form::close() !!}