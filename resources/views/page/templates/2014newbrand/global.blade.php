<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#" lang="hu_HU">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    @section('meta')
        <title>
            @if(env('APP_ENV') != 'production' && env('APP_DEBUG'))
                [DEV]
            @endif
            {{ $settings['system_sitename'] }}
            @if(!empty($title)) - {{ $title }}@endif
        </title>
        <link rel="Shortcut Icon" type="image/png" href="/templates/2014newbrand/images/faviconf1.png"/>
        <link rel="icon" type="image/png" href="/templates/2014newbrand/images/faviconf1.png"/>

        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="mobile-web-app-capable" content="yes"/>
        <link rel="apple-touch-icon" href="/templates/2014newbrand/images/mobile_logo.png"/>
        <meta name="apple-mobile-web-app-title" content="{{ $settings['system_sitename'] }}">
        <meta name="application-name" content="{{ $settings['system_sitename'] }}">

        <meta name="csrf-token" content="{{ csrf_token() }}"/>

        <!-- Load styles -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800&subset=latin,latin-ext" rel="stylesheet" type="text/css">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"/>
        <link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet"/>

        <link href="{{ URL::asset('templates/2014newbrand/style.css') }}" type="text/css" media="screen" rel="stylesheet"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta property="og:title " content="{{ $title }}">
        <meta property="og:type" content="website"/>
        <meta property="og:site_name" content="{{ $settings['system_sitename'] }}">
        @if (isset($page->featuredImage))
            <meta property="og:image" content="{{ URL::to($page->featuredImage) }}"/>
        @endif
    @show
</head>
<body class="<% bodyClass %>">
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/hu_HU/sdk.js#xfbml=1&appId=117103894972589&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<header>
    @section('header')
        <div class="headerbg">
            <div id="header">
                <div id="logo">
                    <a href="/"><img src="/templates/2014newbrand/images/logo.png" alt=""></a>

                    <div style="clear:both;"></div>
                </div>
                {!! Form::open(array('url' => '/', 'method' => 'GET', 'class'=>'searchform')) !!}
                <div class="reset">
                    <img src="/templates/2014newbrand/images/reset.png" title="{{ _("Reset") }}">
                </div>
                <input type="image" class="submitbutton" src="/templates/2014newbrand/images/search_inactive.png" alt="{{ _("Search") }}">
                <input type="text" placeholder="{{ _("Search") }}" name="s" class="searchbox"{!!  (!empty($search) ? ' value="' . $search . '"' : '') !!}>
                {!! Form::close() !!}
                <div id="topright">
                    @include("page.templates.{$template}.particles.navigation")
                </div>
            </div>
        </div>
    @show
</header>

@yield('content')

@section('footer')
    <footer>
        <div class="footerPlace">
            <div id="morefoot">
                <div class="col1">
                    <h3>{{ _("Search") }}</h3>
                    {!! Form::open(array('url' => '/', 'method' => 'GET', 'id'=>'searchform-footer')) !!}
                    <p>
                        <input type="text" placeholder="{{ _("Search keywords") }}" name="s" id="searchbox" onfocus="this.value=''">
                        <input type="submit" class="submitbutton" value="{{ _("Search") }}">
                    </p>
                    {!! Form::close() !!}
                </div>
                <div class="col2">
                    <div id="linkcat-33" class="widget widget_links">
                        <h3>Partnereink</h3>
                        <ul class="xoxo blogroll">
                            <li>
                                <a href="http://huncrazy.korosikrisztian.hu/">Hun Crazy</a>
                            </li>
                            <li>
                                <a href="http://wolfsvsdevils.uw.hu/" target="_blank">Wolfs vs Devils</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col3">
                    <div id="categories-3" class="widget widget_categories">
                        <h3>Kategóriák</h3>
                        <ul>
                            <li class="cat-item cat-item-7">
                                <a href="/kategoria/eredmenyek/">Eredmények</a>
                                <ul class="children">
                                    <li class="cat-item cat-item-4">
                                        <a href="/kategoria/eredmenyek/befutok/">Befutók</a>
                                    </li>
                                    <li class="cat-item cat-item-3">
                                        <a href="/kategoria/eredmenyek/rajtpoziciok/">Rajtpozíciók</a>
                                    </li>
                                    <li class="cat-item cat-item-37">
                                        <a href="/kategoria/eredmenyek/szabadedzesek/">Szabadedzések</a>
                                    </li>
                                    <li class="cat-item cat-item-36">
                                        <a href="/kategoria/eredmenyek/tesztek/">Tesztek</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="cat-item cat-item-13">
                                <a href="/kategoria/hirek/">Hírek</a>
                                <ul class="children">
                                    <li class="cat-item cat-item-35">
                                        <a href="/kategoria/hirek/bemutatok/">Bemutatók</a>
                                    </li>
                                    <li class="cat-item cat-item-6">
                                        <a href="/kategoria/hirek/ertesulesek/">Értesülések</a>
                                    </li>
                                    <li class="cat-item cat-item-5">
                                        <a href="/kategoria/hirek/nyilatkozatok/">Nyilatkozatok</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="cat-item cat-item-1">
                                <a href="/kategoria/nem-kategorizalt/">Nincs kategorizálva</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="cleared"></div>
            </div>
            <div id="footer">
                <div id="footerleft">
                    <p>Site by
                        <a href="http://korosikrisztian.hu" target="_blank"><img src="/templates/2014newbrand/images/author/logo_small_wo.png" alt="Chris's Design" title="Chris's Design" style="width: 32px; height: 32px; border: none; display: inline-block; margin-bottom: -11px; margin-right: 20px;"/></a>
                        <a href="#top">Vissza a tetejére &uarr;</a></p>
                </div>
                <div id="footerright"></div>
                <div class="cleared"></div>
            </div>
        </div>
        <script src="/templates/2014newbrand/js/cookiechoices.js"></script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function (event) {
                cookieChoices.showCookieConsentBar(
                        'A weboldal sütiket ("cookie-kat") használ; amennyiben folytatja az oldal böngészését elfogadja a sütik használatát.',
                        'Elfogadom',
                        'Továbbiak',
                        'http://f1info.hu/'
                );
            });
        </script>
    </footer>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-9634043-8', 'auto');
        ga('send', 'pageview');

    </script>

@show
</body>
</html>
