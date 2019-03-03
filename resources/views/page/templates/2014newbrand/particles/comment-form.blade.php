<div class="cleared"></div>
<div id="respond">
	@if ( $user )
		<h3>{{ _("Comment") }}</h3>
		<div class="loginBox">
			{{ _("Logged in as:") }}
			{{ $user->name }}. 
			<a href="{{ URL::to("auth/logout") }}" title="{{ _("Log out of this account") }}" class="logoutLink">
				Kijelentkezés &raquo;
			</a>
		</div>
		{!! Form::open(array('url' => 'ajax', 'method' => 'POST', 'id' => 'commentform', 'data-type' => 'AddComment')) !!}
			<div class="commentArea">
				<textarea name="comment" id="commentArea" cols="100%" rows="10" tabindex="4" placeholder="Szólj hozzá..." required></textarea>
			</div>
			<div class="comentSubmit">
				<button name="submit" id="submit" class="submitbutton" type="button">{{ _("Send") }}</button>
				<input type="hidden" name="post_uri" value="{{ $uri }}" />
			</div>
		{!! Form::close() !!}
	@else
		<div class="loginBox">
			<a href="{{ URL::to("auth/login") }}">Be kell jelentkezned</a> hozzászólás küldéséhez.
		</div>
	@endif
</div>