<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\DataServiceProvider;
use Auth;

/**
 * Class PageController
 * @package App\Http\Controllers
 */
class PageController extends BaseController {
    /**
     * @var int
     */
    private $offset = 10;

    /**
     * @var User|bool|null
     */

    private $user = false;
    /**
     * @var int|mixed
     */
    private $access_level = 0;

    /**
     * Create a new controller instance.
     *
     */
	public function __construct()
	{
		//$this->middleware('guest');
		$this->getSettings();

		$this->user = Auth::user();

		if($this->user && isset($this->user->access_level)) {
			$this->access_level = $this->user->access_level;
		}

		if($this->template) {
            \LaravelGettext::setDomain($this->template);
        }
	}

    /**
     * @return mixed
     */
    private function getGlobalAssignments() {
		return DataServiceProvider::getRelatedPosts("");
	}

    /**
     * Show the application welcome screen to the user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
	public function index()
	{
        $uri = \Request::path();
        $originalPage = DataServiceProvider::getPostFromURI($uri);

		$pageNo = 1;
		if(is_numeric(\Input::get('page')) && \Input::get('page') > 0){
			$pageNo = (int)\Input::get('page');
		}

		$p = $pageNo - 1;
		$off = $this->offset;

//		if(count($originalPage) > 0) {
//			$originalPage->comments = DataServiceProvider::getPostComments($originalPage->uri);
//			$originalPage->relatedPosts = DataServiceProvider::getRelatedPosts($originalPage->uri, ($originalPage->post_type == 'post-category'), $p, $off);
//			$originalPage->parentPosts = DataServiceProvider::getPostParents($originalPage->uri);
//		}

		$page = (is_object($originalPage)) ? clone $originalPage : $originalPage;

		$global = new \stdClass;
		$global->relatedPosts = $this->getGlobalAssignments();

		$global->latestPostsCount = DataServiceProvider::getLatestPostsCount();
		$global->latestPostsPages = ceil($global->latestPostsCount / $this->offset);
		$global->latestPosts = DataServiceProvider::getLatestPosts($p);

		\Event::fire('GlobalsLoaded', $global);

//		if(count($page) == 0) {
		if(!is_object($page)) {
			$temp = 'default';
			if (view()->exists("page.templates.{$this->template}.404")) {
				$temp = $this->template;
			}

			return view("page.templates.{$temp}.404")->with(array(
					'page' => $page,
					'originalPage' => $originalPage,
					'global' => $global,
					'uri' => $uri,
					'title' => _('Page does not exists'),
					'template'=> $this->template,
					'settings' => $this->settings,
					'user' => Auth::user()
				)
			);
		}

//		$page->widgets = WidgetController::index();
//		$page->links = LinkController::index();

		\Event::fire('BeforePageLoad', $page);

		if(is_object($page) && $page->post_type == 'attachment' && \Input::get('view') !== 'page') {
			$app = app();
			$controller = $app->make('App\Http\Controllers\AttachmentController');
			return $controller->callAction('get', array('attachment' => $page));
		}

        if($this->template) {
            \LaravelGettext::setDomain($this->template);
        }

		$page->featuredImage = '';
//		foreach($page->relatedPosts as $rel_post) {
//			if($rel_post->post_type == 'attachment' && $rel_post->position !== 'post-thumbnail') {
//				$page->featuredImage = $rel_post->uri;
//				break;
//			}
//		}

		if(!empty(\Input::get('s'))) {
			$global->searchCount = DataServiceProvider::getSearchCount(\Input::get('s'));
			$global->searchPages = ceil($global->searchCount / $this->offset);
			$global->searchResults = DataServiceProvider::getSearch(\Input::get('s'), $p);
		}

		$temp = "page.templates.{$this->template}.content";
		if (view()->exists("page.templates.{$this->template}.particles.{$page->post_type}")) {
			$temp = "page.templates.{$this->template}.particles.{$page->post_type}";
		}

		return view($temp)->with(array(
					'page' => $page,
					'originalPage' => $originalPage,
					'global' => $global,
					'uri' => $uri,
					'title'=> $page->title,
					'links'=> $page->links,
					'template'=> $this->template,
					'settings' => $this->settings,
					'pageNo' => $pageNo,
					'offset' => $this->offset,
					'search' => \Input::get('s'),
					'user' => Auth::user()
				)
			);
	}

}
