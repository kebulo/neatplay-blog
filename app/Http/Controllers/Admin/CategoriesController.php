<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CategoriesController extends BaseController
{
    /**
     * @var CategoryContract
     */
    protected $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryContract $categoryRepository  -> Category db table handler
     */
    public function __construct(CategoryContract $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Loads the blogs based on the category id provided
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function getCategoriesBlogsPage()
    {
        try {
            $categories = $this->categoryRepository->listCategories();

            $this->setPageTitle('Categories', 'List of all categories');

            return view('admin.categories.index', compact('categories'));
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
     * Loads the admin list of categories with the spected view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');

            $categories = $this->categoryRepository->listCategories($search);

            $this->setPageTitle('Categories', 'List of all categories');
            return view('admin.categories.index', compact('categories'));
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
     * Loads the categories admin create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $this->setPageTitle('Categories', 'Create Category');
            return view('admin.categories.create');
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
     * Handle the request to create a new categorie based on the parameters provided ($request)
     * @param Request $request -> Contain the params to create a new category, only the name is required
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|max:191',
            ]);

            $params = $request->except('_token');

            $category = $this->categoryRepository->createCategory($params);

            if (!$category) {
                return $this->responseRedirectBack('Error occurred while creating category.', 'error', true, true);
            }
            return $this->responseRedirect('admin.categories.index', 'Category added successfully', 'success', false, false);
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while creating the category: Database query error', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while creating the category: Article not found', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }

    /**
     * Loads the categories admin update form with the category data based on the ID provided
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id) {
        try {
            $category = $this->categoryRepository->findCategoryById($id);

            $this->setPageTitle('Categories', 'Edit Category : ' . $category->name);

            return view('admin.categories.edit', compact('category'));
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
     * Handle the request to update a categoy based on the parameters provided ($request)
     * @param Request $request -> Contains the category data, only the name and the ID are required
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required',
                'name' => 'required|max:250',
            ]);

            $params = $request->except('_token');

            $category = $this->categoryRepository->updateCategory($params);

            if (!$category) {
                return $this->responseRedirectBack('Error occurred while updating category.', 'error', true, true);
            }
            return $this->responseRedirectBack('Category updated successfully', 'success', false, false);
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while updating the category: Database query error. Please check the data and try again.', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while updating the category: Could not find the resource', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }

    /**
     * Handle the request to delete a category based on the ID
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $rules = [
                'id' => 'required|integer|exists:categories,id',
            ];

            $validator = Validator::make(['id' => $id], $rules);

            if ($validator->fails()) {
                return redirect()->route('admin.blogs.index')->withErrors($validator)->withInput();
            }

            $category = $this->categoryRepository->deleteCategory($id);

            if (!$category) {
                return $this->responseRedirectBack('Error occurred while deleting category.', 'error', true, true);
            }
            return $this->responseRedirect('admin.categories.index', 'Category deleted successfully', 'success', false, false);
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while deleting the category: Database query error. Please check the data and try again.', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while deleting the category: Could not find the resource', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }
}