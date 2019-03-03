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
        	{!! Form::open(array('url' => 'auth/forgotten-password', 'method' => 'POST')) !!}
				<p>
					{!! Form::label('email', _('Email address')) !!}
					{!! Form::text('email', Input::old('email'), array('placeholder' => 'awesome@awesome.com', 'class' => 'input')) !!}
				</p>
			    <p class="forgotten-password-submit">
					{!! Form::submit( _('Send'), array('class'=>"button-primary") ) !!}
			    </p>
			{!! Form::close() !!}
        </div>
    </div>

    <a href="javascript:void(0)" class="switch-mobile"></a>
	<div class="cleared"></div>
</div>
@stop
