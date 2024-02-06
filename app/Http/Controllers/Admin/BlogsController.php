<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\BlogsContract;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function __construct(BlogsContract $blogRepository, CategoryContract $categoryRepository)
    {
        $this->blogRepository = $blogRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all the articles in the table and return the view with the data.
     * @param $request: Request -> Search param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $blogs = $this->blogRepository->listBlogs($search);

            $this->setPageTitle('Articles', 'List of all articles');

            return view('admin.blogs.index', compact('blogs'));
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while loading the list view: Database query error', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while loading the list view: Article not found', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }

    /**
     * Load the HTMl to create a new article with the categories available.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $this->setPageTitle('Article', 'Create an Article');
            $categories = $this->categoryRepository->listCategories(null, 'name', 'asc');

            return view('admin.blogs.create', compact('categories'));
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while loading the creation view: Database query error', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while loading the creation view: Article not found', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
        
    }

    /**
     * Handle the request to create a new article into the database
     * @param $request Request -> Article data, only the title is required.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required'
            ]);

            $params = $request->except('_token'); 

            $blog = $this->blogRepository->createBlog($params);

            if (!$blog) {
                return $this->responseRedirectBack('Error occurred while creating the article.', 'error', true, true);
            }

            return $this->responseRedirect('admin.blogs.index', 'The article was added successfully', 'success', false, false);
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while creating the article: Database query error. Please check the data and try again.', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while creating the article: Could not find the resource', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }

    /**
     * Load the HTMl to update an article wiht the categories available.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        try {
            $blog = $this->blogRepository->findBlogById($id);
            $categories = $this->categoryRepository->listCategories(null, 'name', 'asc');
            $this->setPageTitle('Article', 'Edit Article : ' . $blog->title);
            return view('admin.blogs.edit', compact('blog', 'categories'));
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while loading the edit view: Database query error', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while loading the edit view: Article not found', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }

    /**
     * Handle the request to update an article based on the ID and the data provided
     * @param $request Request -> Article data, only the title is required, the data not provided is going to be removed.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function update(Request $request)
    {
        try {
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
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while updating the article: Database query error. Please check the data and try again.', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while updating the article: Could not find the resource', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }

    /**
     * Handle the request to delete an article based on the ID provided
     * @param $id -> Number or String Article ID.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function delete($id)
    {
        try {
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
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while deleting the article: Database query error. Please check the data and try again.', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while deleting the article: Could not find the resource', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }
}
