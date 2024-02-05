<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Contracts\CategoryContract;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCategoriesBlogsPage()
    {
        $categories = $this->categoryRepository->listCategories();

        $this->setPageTitle('Categories', 'List of all categories');
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Loads the admin list of categories with the spected view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = $this->categoryRepository->listCategories($search);

        $this->setPageTitle('Categories', 'List of all categories');
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Loads the categories admin create form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->setPageTitle('Categories', 'Create Category');
        return view('admin.categories.create');
    }

    /**
     * Handle the request to create a new categorie based on the parameters provided ($request)
     * @param Request $request -> Contain the params to create a new category, only the name is required
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:191',
        ]);

        $params = $request->except('_token');

        $category = $this->categoryRepository->createCategory($params);

        if (!$category) {
            return $this->responseRedirectBack('Error occurred while creating category.', 'error', true, true);
        }
        return $this->responseRedirect('admin.categories.index', 'Category added successfully', 'success', false, false);
    }

    /**
     * Loads the categories admin update form with the category data based on the ID provided
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->findCategoryById($id);

        $this->setPageTitle('Categories', 'Edit Category : ' . $category->name);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Handle the request to update a categoy based on the parameters provided ($request)
     * @param Request $request -> Contains the category data, only the name and the ID are required
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
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
    }

    /**
     * Handle the request to delete a category based on the ID
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
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
    }
}