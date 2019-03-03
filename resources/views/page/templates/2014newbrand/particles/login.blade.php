@extends("page.templates.{$template}.content")

@section('page-content')
    <div id="main">
      <!-- get_sidebar('full') -->
    </div>
    <div id="wrapper" class="page-template-page_fullwidth-php">
        <div class="topPostHeader">
            <h2 class="topTitle">
                <span>{{ _("Login") }}</span>
            </h2>
        </div>

        <div id="contentwrapper2">
            <!-- if there are login errors, show them here -->
            <p>
                {{ $errors->first('email') }}
                {{ $errors->first('password') }}
            </p>

            @include("page.templates.{$template}.particles.login-form")

            <a href="javascript:void(0)" class="switch-mobile"></a>
            <div class="cleared"></div>
        </div>
    </div>

@endsection
