@extends("page.templates.{$template}.content")

@section('page-content')
    <div id="main">
      @include("page.templates.{$template}.particles.sidebar-full", array('relatedPosts' => $global->relatedPosts))
    </div>
    <div id="wrapper">
        <div class="cleared"></div>
        
        @if (count($page->relatedPosts) > 0 && false)
        <div class="postImage" title="{{ $page->relatedPosts[0]->title }}" style="background-image: url('{{ URL::to($page->relatedPosts[0]->featuredImage) }}');"> 
          <div class="slideInfoZone">
            <h2 class="topTitle"><span>{{ $page->relatedPosts[0]->title }}</span></h2>
            <div class="excerpt">
                <p>{{ $page->relatedPosts[0]->excerpt }}</p>
            </div>
          </div>
        </div>
        @endif
        <div id="contentwrapper">
        	<div class="topPostHeader">
            	<h2 class="topTitle">
				  {{ $page->title }}
                </h2>
            </div>
            @foreach ($page->relatedPosts as $i=>$relatedPost)
            <div class="topPost" style="border: 0px solid transparent">
                <div class="TitleBg">
                    <div class="postDay">
                        <span class="day">{{ strftime('%d', strtotime($relatedPost->created_at)) }}</span>
                        <span class="month">{{ strtolower(strftime('%b', strtotime($relatedPost->created_at))) }}</span>
                    </div>
                    <h2 class="topTitle2">
                        <a href="{{ URL::to($relatedPost->uri) }}">
                            {!! $relatedPost->title !!}
                        </a>
                    </h2>
                    <p class="topMeta2">
                        {{ strtolower(strftime(_('%Y. %B. %d.'), strtotime($relatedPost->created_at))) }},
                        kategória: 
                        @foreach ($relatedPost->parentPosts as $category)
                        @if ($category->post_type == 'post-category')
                            <a href="{{ URL::to($category->uri) }}" rel="category tag">{{ $category->title }}</a>
                        @endif
                        @endforeach
                    </p>
                    <div style="clear: both;height:0;"></div>
                </div>
                <div class="shadowed">
                    <div class="topContent2"> 
                        <a href="{{ URL::to($relatedPost->uri) }} " class="thumbnail">
                        @foreach ($relatedPost->relatedPosts as $rel_post)
                            @if($rel_post->post_type == 'attachment' && $rel_post->position == 'post-thumbnail')
                            <img width="100" height="100" src="{{ URL::to($rel_post->uri) }}" class="thumbnail wp-post-image" alt="">
                            @endif
                        @endforeach
                        </a>
                        <p>
                            {!! $relatedPost->excerpt !!} 
                        </p>
                        <div class="cleared"></div>
                    </div>
                    <div class="cleared">
                        <div class="article-separator">&nbsp;</div>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div id="nextprevious">
                <div class="alignleft">
                	<a href="?page={{ ($pageNo+1) }}">
                    	<span class="pagerText">&laquo; korábbi bejegyzések</span>
                    </a>
                </div>
                @if ($pageNo > 2)
                <div class="alignright">
                	<a href="?page={{ ($pageNo-1) }}">
                    	<span class="pagerText">későbbi bejegyzések &raquo;</span>
                    </a>
                </div>
                @elseif ($pageNo == 2)
                <div class="alignright">
                    <a href="?">
                        <span class="pagerText">későbbi bejegyzések &raquo;</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
        <div id="sidebars">
        	@include("page.templates.{$template}.particles.sidebar-right", array('relatedPosts' => $global->relatedPosts))
        </div>
        
        <a href="javascript:void(0)" class="switch-mobile"></a>
        <div class="cleared"></div>
    </div>
@stop