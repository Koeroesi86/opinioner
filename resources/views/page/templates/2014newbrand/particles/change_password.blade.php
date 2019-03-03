@extends("page.templates.{$template}.content")

@section('page-content')
<div id="main">
	<!-- get_sidebar('full') -->
</div>
<div id="wrapper" class="page-template-page_fullwidth-php">
	<div class="topPostHeader">
		<h2 class="topTitle">
			<span>{{ $title }}</span>
		</h2>
	</div>
	<div class="topPost">
    	<div class="topContent">
        	{!! Form::open(array('url' => 'auth/change-password', 'method' => 'POST', 'class' => 'change-password fullpage')) !!}
				<p>
	                {!! Form::label('password', _('New Password')) !!}
	                {!! Form::password('password') !!}
	            </p>
	            <p>
	                {!! Form::label('password_confirmation', _('Confirm Password')) !!}
	                {!! Form::password('password_confirmation') !!}
	            </p>
			    <p class="change-password-submit">
			        {!! Form::submit( _('Login'), array('class'=>"button-primary") ) !!}
			    </p>
			{!! Form::close() !!}
        </div>
    </div>

    <a href="javascript:void(0)" class="switch-mobile"></a>
	<div class="cleared"></div>
</div>
@stop