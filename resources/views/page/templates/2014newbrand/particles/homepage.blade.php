@extends("page.templates.{$template}.content")

@section('page-content')
    <div id="main">
      @include("page.templates.{$template}.particles.sidebar-full", array('relatedPosts' => $global->relatedPosts))
    </div>
    <div id="wrapper">
        <div class="cleared"></div>
        
        @if (count($global->latestPosts) > 0 && $pageNo == 1 && !isset($global->searchResults))
        <a href="{{ URL::to($global->latestPosts[0]->uri) }}">
            <div class="postImage" title="{{ $global->latestPosts[0]->title }}" style="background-image: url('{{ URL::to($global->latestPosts[0]->featuredImage) }}');"> 
              <!--<img src="" class="singleFeatured">-->
              <div class="slideInfoZone">
                <h2 class="topTitle"><span>{{ $global->latestPosts[0]->title }}</span></h2>
                <div class="excerpt">
                    <p>{{ $global->latestPosts[0]->excerpt }}</p>
                </div>
              </div>
            </div>
        </a>
        @endif
        
        <div id="contentwrapper">
            @if ($pageNo > 1)
            <div class="topPostHeader">
                <h2 class="topTitle">
                  @if (!isset($global->searchResults))
                  {{ $page->title }}
                  @else
                  {{ _("Search") . " (" . $global->searchCount . _(" results") . ")" }}
                  @endif
                </h2>
            </div>
            @endif
            
            @if (!isset($global->searchResults))
                @foreach($global->latestPosts as $i=>$latestPost)
                @if($i > 0 || $pageNo !== 1)
                <div class="topPost" style="border: 0px solid transparent">
                    <div class="TitleBg">
                        <div class="postDay">
                            <span class="day">{{ strftime('%d', strtotime($latestPost->created_at)) }}</span>
                            <span class="month">{{ strtolower(strftime('%b', strtotime($latestPost->created_at))) }}</span>
                        </div>
                        <h2 class="topTitle2">
                            <a href="{{ URL::to($latestPost->uri) }}">
                                {!! $latestPost->title !!}
                            </a>
                        </h2>
                        <p class="topMeta2">
                            {{ strtolower(strftime(_('%Y. %B. %d.'), strtotime($latestPost->created_at))) }},
                            kategória: 
                            @foreach ($latestPost->parentPosts as $category)
                            @if ($category->post_type == 'post-category')
                                <a href="{{ URL::to($category->uri) }}" rel="category tag">{{ $category->title }}</a>
                            @endif
                            @endforeach
                        </p>
                        <div style="clear: both;height:0;"></div>
                    </div>
                    <div class="shadowed">
                        <div class="topContent2"> 
                            @if (count($latestPost->relatedPosts) > 0)
                            <a href="{{ URL::to($latestPost->uri) }} " class="thumbnail">
                            @foreach($latestPost->relatedPosts as $rel_post)
                                @if($rel_post->post_type == 'attachment' && $rel_post->position == 'post-thumbnail')
                                <img width="100" height="100" src="{{ URL::to($rel_post->uri) }}" class="thumbnail wp-post-image" alt="">
                                @endif
                            @endforeach
                            </a>
                            @endif
                            <p>
                                {!! $latestPost->excerpt !!} 
                            </p>
                            <div class="cleared"></div>
                        </div>
                        <div class="cleared">
                            <div class="article-separator">&nbsp;</div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

                <div id="nextprevious">
                    <div class="alignleft previous-link">
                        @if ($global->latestPostsPages > $pageNo)
                        <a href="?page={{ ($pageNo+1) }}">
                        @else
                        <a href="?page={{ $pageNo }}">
                        @endif
                            <span class="pagerText">&laquo; korábbi bejegyzések</span>
                        </a>
                    </div>

                    <div class="page-numbers">
                        @if ($pageNo < $global->latestPostsPages - 2)
                            <a href="?page={{ $global->latestPostsPages }}">
                                <span>{{ $global->latestPostsPages }}</span>
                            </a>
                            @if ($pageNo < $global->latestPostsPages - 3)
                            ...
                            @endif
                        @endif

                        @for ($i = $global->latestPostsPages; $i > 0; $i--) 
                            @if ($i >= $pageNo - 2 && $i <= $pageNo + 2)
                            <a href="?{{ ($i > 1 ? 'page=' . $i : '') }}"{!! ($i == $pageNo ? ' class="current"' : '') !!}>
                                <span>{{ $i }}</span>
                            </a>
                            @endif
                        @endfor

                        @if ($pageNo > 3)
                            @if ($pageNo > 4)
                            ...
                            @endif
                            <a href="?">
                                <span>1</span>
                            </a>
                        @endif
                    </div>

                    @if ($pageNo > 2)
                    <div class="alignright next-link">
                        <a href="?page={{ ($pageNo-1) }}">
                            <span class="pagerText">későbbi bejegyzések &raquo;</span>
                        </a>
                    </div>
                    @elseif ($pageNo <= 2)
                    <div class="alignright next-link">
                        <a href="?">
                            <span class="pagerText">későbbi bejegyzések &raquo;</span>
                        </a>
                    </div>
                    @endif
                </div>
            @else
                @if (count($global->searchResults) > 0 && is_array($global->searchResults))
                @foreach($global->searchResults as $i=>$latestPost)
                <div class="topPost" style="border: 0px solid transparent">
                    <div class="TitleBg">
                        <div class="postDay">
                            <span class="day">{{ strftime('%d', strtotime($latestPost->created_at)) }}</span>
                            <span class="month">{{ strtolower(strftime('%b', strtotime($latestPost->created_at))) }}</span>
                        </div>
                        <h2 class="topTitle2">
                            <a href="{{ URL::to($latestPost->uri) }}">
                                {!! $latestPost->title !!}
                            </a>
                        </h2>
                        <p class="topMeta2">
                            {{ strtolower(strftime(_('%Y. %B. %d.'), strtotime($latestPost->created_at))) }},
                            kategória: 
                            @foreach ($latestPost->parentPosts as $category)
                            @if ($category->post_type == 'post-category')
                                <a href="{{ URL::to($category->uri) }}" rel="category tag">{{ $category->title }}</a>
                            @endif
                            @endforeach
                        </p>
                        <div style="clear: both;height:0;"></div>
                    </div>
                    <div class="shadowed">
                        <div class="topContent2"> 
                            @if (count($latestPost->relatedPosts) > 0)
                            <a href="{{ URL::to($latestPost->uri) }} " class="thumbnail">
                            @foreach($latestPost->relatedPosts as $rel_post)
                                @if($rel_post->post_type == 'attachment' && $rel_post->position == 'post-thumbnail')
                                <img width="100" height="100" src="{{ URL::to($rel_post->uri) }}" class="thumbnail wp-post-image" alt="">
                                @endif
                            @endforeach
                            </a>
                            @endif
                            <p>
                                {!! $latestPost->excerpt !!} 
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
                    <div class="alignleft previous-link">
                        @if ($global->searchPages > $pageNo)
                        <a href="?page={{ ($pageNo+1) . '&s=' . $search }}">
                        @else
                        <a href="?page={{ $pageNo . '&s=' . $search }}">
                        @endif
                            <span class="pagerText">&laquo; korábbi bejegyzések</span>
                        </a>
                    </div>

                    <div class="page-numbers">
                        @if ($pageNo < $global->searchPages - 3)
                        <a href="?page={{  $global->searchPages . '&s=' . $search }}">
                            <span>{{ $global->searchPages }}</span>
                        </a>
                        ...
                        @endif

                        @for ($i = $global->searchPages; $i > 0; $i--) 
                            @if ($i >= $pageNo - 2 && $i <= $pageNo + 2)
                            <a href="?page={{ $i . '&s=' . $search }}"{!! ($i == $pageNo ? ' class="current"' : '') !!}>
                                <span>{{ $i }}</span>
                            </a>
                            @endif
                        @endfor

                        @if ($pageNo > 3)
                        ...
                        <a href="?{{  (!empty($search) ? '&s=' . $search : '') }}">
                            <span>1</span>
                        </a>
                        @endif
                    </div>

                    @if ($pageNo > 2)
                    <div class="alignright next-link">
                        <a href="?page={{ ($pageNo-1) . (!empty($search) ? '&s=' . $search : '') }}">
                            <span class="pagerText">későbbi bejegyzések &raquo;</span>
                        </a>
                    </div>
                    @elseif ($pageNo <= 2)
                    <div class="alignright next-link">
                        <a href="?{{ (!empty($search) ? 's=' . $search : '') }}">
                            <span class="pagerText">későbbi bejegyzések &raquo;</span>
                        </a>
                    </div>
                    @endif
                </div>
                @else
                    <div class="topPostHeader">
                        <h2 class="topTitle">{{ _("I cannot find anything") }}</h2>
                    </div>
                    <div class="topPost">
                        <div class="topContent">
                            <p>
                                {{ _("Sorry, but you're looking for something what is not exists here. You can try again your search:") }}
                            </p>
                            {!! Form::open(array('url' => '/', 'method' => 'GET', 'id'=>'searchform')) !!}
                                <p>
                                    <input type="text" placeholder="{{ _("Search keywords") }}" name="s" id="searchbox" onfocus="this.value=''">
                                    <input type="submit" class="submitbutton" value="{{ _("Search") }}">
                                </p>
                            {!! Form::close() !!}
                        </div>
                    </div>
                @endif
            @endif
        </div>
        <div id="sidebars">
        	@include("page.templates.{$template}.particles.sidebar-right", array('relatedPosts' => $global->relatedPosts))
        </div>
        
        <a href="javascript:void(0)" class="switch-mobile"></a>
        <div class="cleared"></div>
    </div>
@stop