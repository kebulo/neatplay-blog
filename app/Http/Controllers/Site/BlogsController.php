<?php

namespace App\Http\Controllers\Site;

use App\Contracts\BlogsContract;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Blog Controller manages all the views and the bridge between the repository (database methods) and the public site requests
 */
class BlogsController extends BaseController
{
    protected $blogRepository;
    protected $categoryRepository;

    /**
     * BlogsController constructor.
     * @param BlogsContract $blogRepository -> Blog db table handler
     * @param CategoryContract $categoryRepository -> Category db table handler
     */
    public function __construct(BlogsContract $blogRepository, CategoryContract $categoryRepository,)
    {
        $this->blogRepository = $blogRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all the articles in the table and return the view with the data.
     * @param $request: Request -> Search param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeBlogs() {
        $blogs = $this->blogRepository->listHomeBlogs('publication_date', 'desc');
        $username = (Auth::user())?Auth::user()->full_name:'';

        $this->setPageTitle('Articles', 'List of all the articles');

        return view('site.pages.homepage', compact('blogs', 'username'));
    }

    /**
     * Get one article in the table based on the ID provided and return the view with the data.
     * @param $title: String -> Title of the article
     * @param $id: Number -> Unique identificator of the article
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function homeBlog($title, $id) {
        $blog = $this->blogRepository->findHomeBlogById($id);

        $this->setPageTitle('Article', $blog->title);

        return view('site.pages.blog', compact('blog'));
    }

}
