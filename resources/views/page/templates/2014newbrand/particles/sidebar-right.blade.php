<div id="sidebar_right">
	<ul>
        <li class="widget_wp_sidebarlogin">
	        <div class="sidebarbox">
	        @if (!isset($user) || !$user)
	            <h2 class="widgettitle">
	                <span><div>{!! _("Login") !!}</div></span>
	            </h2>

	            @include("page.templates.{$template}.particles.login-form")
	        @else
	            <h2 class="widgettitle">
					<span><div>{{ _("Welcome, %s", $user->name) }}</div></span>
	            </h2>
	            <ul class="pagenav sidebar_login_links">
					@if (isset($user) && isset($originalPage) && isset($global) && $user->access_level > 1)
						<li>
							<a href="{{ url("admin") }}?{{http_build_query([ "from" => \Request::path()]) }}" target="_blank">{{ _("Admin") }}</a>
						</li>
					@endif
	                <li>
	                    <a href="{{ url("auth/change-password") }}">{{ _("Change password") }}</a>
	                </li>
	                <li>
	                    <a href="{{ url("auth/logout") }}">{{ _("Logout") }}</a>
	                </li>
	            </ul>
	        @endif
	        </div>
        </li>
		@if (is_iterable($relatedPosts))
			@foreach($relatedPosts as $relatedPost)
				@if ($relatedPost->position == 'sidebar-right')
					<li>
						<div class="sidebarbox">
							<h2 class="widgettitle">
								<span><div>{!! $relatedPost->title !!}</div></span>
							</h2>
							{!! $relatedPost->body !!}
						</div>
					</li>
				@endif
			@endforeach
		@endif
	    <li>
	    	<a href="http://www.facebook.com/f1info.hu" target="_blank" title="Kövess minket facebookon is!" style="display: block;text-align: center;margin-left: -10px; margin-top: -15px;"><img src="/templates/2014newbrand/images/facebook.png"></a>
	    </li>
	</ul>
</div>
