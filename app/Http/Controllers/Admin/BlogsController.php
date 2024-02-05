<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\BlogsContract;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Blog Controller manages all the views and the bridge between the repository (database methods) and the admin requests
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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $blogs = $this->blogRepository->listBlogs($search);

        $this->setPageTitle('Articles', 'List of all articles');

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Load the HTMl to create a new article wiht the categories available.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->setPageTitle('Article', 'Create an Article');
        $categories = $this->categoryRepository->listCategories(null, 'name', 'asc');

        return view('admin.blogs.create', compact('categories'));
    }

    /**
     * Handle the request to create a new article into the database
     * @param $request Request -> Article data, only the title is required.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $params = $request->except('_token'); 

        $blog = $this->blogRepository->createBlog($params);

        if (!$blog) {
            return $this->responseRedirectBack('Error occurred while creating the article.', 'error', true, true);
        }

        return $this->responseRedirect('admin.blogs.index', 'The article was added successfully', 'success', false, false);
    }

    /**
     * Load the HTMl to update an article wiht the categories available.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $blog = $this->blogRepository->findBlogById($id);
        $categories = $this->categoryRepository->listCategories(null, 'name', 'asc');

        $this->setPageTitle('Article', 'Edit Article : ' . $blog->title);

        return view('admin.blogs.edit', compact('blog', 'categories'));
    }

    /**
     * Handle the request to update an article based on the ID and the data provided
     * @param $request Request -> Article data, only the title is required, the data not provided is going to be removed.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'title' => 'required',
        ]);   

        $params = $request->except('_token');

        $blog = $this->blogRepository->updateBlog($params); 

        if (!$blog) {
            return $this->responseRedirectBack('Error occurred while updating the article.', 'error', true, true);
        }

        return $this->responseRedirectBack('The article was updated successfully', 'success', false, false);
    }

    /**
     * Handle the request to delete an article based on the ID provided
     * @param $request Request -> Article data, only the title is required, the data not provided is going to be removed.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function delete($id)
    {
        $rules = [
            'id' => 'required|integer|exists:blog,id',
        ];

        $validator = Validator::make(['id' => $id], $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.blogs.index')->withErrors($validator)->withInput();
        }

        $blog = $this->blogRepository->deleteBlog($id);

        if (!$blog) {
            return $this->responseRedirectBack('Error occurred while deleting the article.', 'error', true, true);
        }

        return $this->responseRedirect('admin.blogs.index', 'The article was deleted successfully', 'success', false, false);
    }
}
