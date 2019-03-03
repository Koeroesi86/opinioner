@extends("page.templates.{$template}.content")

@section('page-content')
    <div id="main">
      @include("page.templates.{$template}.particles.sidebar-full", array('relatedPosts' => $global->relatedPosts))
    </div>
    <div id="wrapper">
        <div class="cleared"></div>
        
        <div class="postImage" title="{{ $page->title }}" style="background-image: url('{{ URL::to($page->featuredImage) }}');"> 
          <!--<img src="" class="singleFeatured">-->
          <div class="slideInfoZone">
            <h2 class="topTitle"><span>{{ $page->title }}</span></h2>
            <div class="excerpt">
                <p>{{ $page->excerpt }}</p>
            </div>
          </div>
        </div>
        <div id="contentwrapper">
            <div class="topPost">
                <div class="topContent">
                    <div class="socialButtons">
                        <div class="shareText">Megosztás:</div>
                        <div class="buttonHolder">
                            <div class="buttonText">
                                <i class="fa fa-facebook-square"></i> Facebook
                            </div>
                            <div class="fb-like" data-layout="button" data-colorscheme="light" data-action="like" data-show-faces="false" data-share="true" data-width="210"></div>
                        </div>
                        <div class="buttonHolder">
                        	<div class="buttonText">
                        		<i class="fa fa-google-plus-square"></i> Google+
                            </div>
                        	<div class="g-plusone" data-size="medium" data-annotation="none"></div>
                        </div>
                        <div class="buttonHolder">
                        	<div class="buttonText">
                            	<i class="fa fa-twitter-square"></i> Twitter
                            </div>
                        	<a href="https://twitter.com/share" class="twitter-share-button" data-lang="hu" data-count="none" data-size="small" target="_blank">Tweet</a>
                        </div>
                    </div>
                    {!! $page->body !!}
                    <div class="socialButtons">
                        <div class="shareText">Megosztás:</div>
                        <div class="buttonHolder">
                            <div class="buttonText">
                                <i class="fa fa-facebook-square"></i> Facebook
                            </div>
                            <div class="fb-like" data-layout="button" data-colorscheme="light" data-action="like" data-show-faces="false" data-share="true" data-width="210"></div>
                        </div>
                        <div class="buttonHolder">
                        	<div class="buttonText">
                        		<i class="fa fa-google-plus-square"></i> Google+
                            </div>
                        	<div class="g-plusone" data-size="medium" data-annotation="none"></div>
                        </div>
                        <div class="buttonHolder">
                        	<div class="buttonText">
                            	<i class="fa fa-twitter-square"></i> Twitter
                            </div>
                        	<a href="https://twitter.com/share" class="twitter-share-button" data-lang="hu" data-count="none" data-size="small" target="_blank">Tweet</a>
                        </div>
                    </div>
                </div>
                <div class="cleared"></div>
            </div>
            <div id="comment">
                @include("page.templates.2014newbrand.particles.comments", array('user' => $user, 'uri' => $uri, 'comments' => $page->comments))
            </div>
        </div>
        <div id="sidebars">
        	@include("page.templates.{$template}.particles.sidebar-right", array('relatedPosts' => $global->relatedPosts))
        </div>
        
        <a href="javascript:void(0)" class="switch-mobile"></a>
        <div class="cleared"></div>
    </div>
@stop