<?php

namespace App\Http\Controllers\Site;

use App\Contracts\BlogsContract;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogsController extends BaseController
{
    protected $blogRepository;
    protected $categoryRepository;

    public function __construct(BlogsContract $blogRepository, CategoryContract $categoryRepository,)
    {
        $this->blogRepository = $blogRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function homeBlogs() {
        $blogs = $this->blogRepository->listHomeBlogs('publication_date', 'desc');
        $username = (Auth::user())?Auth::user()->full_name:'';

        $this->setPageTitle('blogs', 'List of all blogs');

        return view('site.pages.homepage', compact('blogs', 'username'));
    }
    public function homeBlog($title, $id) {
        $blog = $this->blogRepository->findBlogById($id);

        $this->setPageTitle('Article', $blog->title);

        return view('site.pages.blog', compact('blog'));
    }

}
