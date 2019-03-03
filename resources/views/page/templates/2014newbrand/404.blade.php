@extends("page.templates.{$template}.content")

@section('page-content')
	<div id="main">
		<div class="sidebarbox">
			&nbsp;
		</div>
	</div>
	<div id="wrapper" class="page-template-page_fullwidth-php">
		<div class="topPostHeader">
			<h2 class="topTitle">
				<span>{{ $title }}</span>
			</h2>
		</div>
		<div id="contentwrapper2">
			<div class="topPost">
				<div class="topContent">
					<p>
						{{ _("Unfortunately the page you've specified does not exists:") }}
						<pre title="{{{ $uri }}}">{{{ URL::to($uri) }}}</pre>
					</p>
				</div>
			</div>
		</div>

		<a href="javascript:void(0)" class="switch-mobile"></a>
		<div class="cleared"></div>
	</div>
@stop