<?php
namespace App\Repositories;

use App\Models\Category;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use App\Contracts\CategoryContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class CategoryRepository
 *
 * @package \App\Repositories
 */
class CategoryRepository extends BaseRepository implements CategoryContract
{
    use UploadAble;

    /**
     * CategoryRepository constructor.
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listCategories(string $search=null, string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        try {
            $query = $this->model->orderBy($order, $sort);

            // If there's a search query, add a where clause for the title
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $query->where('user_id', Auth::user()->id);

            return $query->select($columns)->get();
         } catch (Exception $e) {
            Log::error('Error occurred while searching the categories: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findCategoryById(int $id)
    {
        try {
            return $this->findOneOrFail($id);
        } catch (Exception $e) {
            Log::error('Error occurred while searching the category based on the ID: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return Category|mixed
     */
    public function createCategory(array $params)
    {
        try {
            $collection = collect($params);

            $user_id = auth()->id();

            $merge = $collection->merge(compact('user_id'));

            $category = new Category($merge->all());

            $category->save();

            return $category;

        } catch (Exception $e) {
            Log::error('Error occurred while creating the category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateCategory(array $params)
    {
        try {
            $category = $this->findCategoryById($params['id']);

            $user_id = Auth::user()->id;

            if ($category->user_id && $category->user_id != $user_id) {
                throw new \Exception("Invalid user ID provided.");
            }

            $collection = collect($params)->except('_token');

            $user_id = auth()->id();

            $merge = $collection->merge(compact('user_id'));

            $category->update($merge->all());

            return $category;
         } catch (Exception $e) {
            Log::error('Error occurred while updating the category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteCategory($id)
    {
        try {
            $category = $this->findCategoryById($id);

            $user_id = Auth::user()->id;

            if ($category->user_id && $category->user_id != $user_id) {
                throw new \Exception("Invalid user ID provided.");
            }

            $category->delete();

            return $category;
         } catch (Exception $e) {
            Log::error('Error occurred while deleting the category: ' . $e->getMessage());
            throw $e;
        }
    }
}