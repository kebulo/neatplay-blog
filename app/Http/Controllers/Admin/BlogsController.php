<?php

namespace App\Http\Controllers\Admin;

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
        $blogs = $this->blogRepository->listBlogs('publish_date', 'desc', ['id', 'title', 'content']);

        $this->setPageTitle('blogs', 'List of all blogs');

        return view('site.pages.homepage', compact('blogs'));
    }
    public function homeBlog($title, $id) {
        $blog = $this->blogRepository->findBlogById($id);

        $this->setPageTitle('Article', $blog->title);

        return view('site.pages.blog', compact('blog'));
    }

    public function index()
    {
        $blogs = $this->blogRepository->listBlogs();

        $this->setPageTitle('blogs', 'List of all blogs');

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $this->setPageTitle('blogs', 'Create blog');
        $categories = $this->categoryRepository->listCategories('name', 'asc');

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $params = $request->except('_token'); 

        $blog = $this->blogRepository->createBlog($params);

        if (!$blog) {
            return $this->responseRedirectBack('Error occurred while creating the blog.', 'error', true, true);
        }

        return $this->responseRedirect('admin.blogs.index', 'The blog was added successfully', 'success', false, false);
    }

    public function edit($id)
    {
        $blog = $this->blogRepository->findBlogById($id);
        $categories = $this->categoryRepository->listCategories('name', 'asc');

        $this->setPageTitle('blogs', 'Edit blog : ' . $blog->title);

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);   

        $params = $request->except('_token');

        $blog = $this->blogRepository->updateBlog($params);  

        if (!$blog) {
            return $this->responseRedirectBack('Error occurred while updating the blog.', 'error', true, true);
        }

        return $this->responseRedirectBack('The blog was updated successfully', 'success', false, false);
    }

    public function delete($id)
    {
        $blog = $this->blogRepository->deleteBlog($id);

        if (!$blog) {
            return $this->responseRedirectBack('Error occurred while deleting the blog.', 'error', true, true);
        }

        return $this->responseRedirect('admin.blogs.index', 'The blog was deleted successfully', 'success', false, false);
    }
}
