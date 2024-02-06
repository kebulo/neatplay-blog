<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Contracts\BlogsContract;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Auth;

class BlogsRepository extends BaseRepository implements BlogsContract
{
    use UploadAble;

    /**
     * BlogRepository constructor.
     * @param Blog $model
     */
    public function __construct(Blog $model)
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
    public function listBlogs($search = null, string $order = 'id', string $sort = 'desc', array $columns = ['*'], int $perPage = 9)
    {
        try {
            $query = $this->model->orderBy($order, $sort);

            // If there's a search query, add a where clause for the title
            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            $query->where('user_id', Auth::user()->id);

            return $query->select($columns)->get();
        } catch (Exception $e) {
            Log::error('Error occurred while searching the articles: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listHomeBlogs(string $order = 'id', string $sort = 'desc', array $columns = ['*'], int $perPage = 9)
    {
        try {
            return $this->model
                ->with('category')
                ->where('public', 1)
                ->orderBy($order, $sort)
                ->select($columns)
                ->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error occurred while searching the articles for the homepage: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findHomeBlogById(int $id){
        try {
            return $this->model->findOrFail($id);

        } catch (Exception $e) {
            Log::error('Error occurred while searching the article based on the ID for the homepage: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findBlogById(int $id){

        try {
            return $this->model->where('user_id', Auth::user()->id)->findOrFail($id);

        } catch (Exception $e) {
            Log::error('Error occurred while creating the article based on the ID: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return Blog | mixed
     */
    public function createBlog(array $params)
    {
        try {
            $collection = collect($params);

            $public = $collection->has('public') ? 1 : 0;
            $user_id = Auth::user()->id;

            $merge = $collection->merge(compact('public', 'user_id'));

            $Blog = new Blog($merge->all());

            $Blog->save();

            return $Blog;

        } catch (Exception $e) {
            Log::error('Error occurred while creating the article: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBlog(array $params)
    {
        try {
            $blog = $this->findBlogById($params['id']);
            $user_id = Auth::user()->id;

            if ($blog->user_id && $blog->user_id != $user_id) {
                throw new \Exception("Invalid user ID provided.");
            }

            $collection = collect($params)->except('_token');

            if ($collection->has('image') && ($params['image'] instanceof UploadedFile)) {
                $image = '';
                $public_path = '';

                if ($blog->image != null) {
                    $this->deleteOne($blog->image);
                }

                $image_data = $this->uploadOne($params['image'], '/blog');

                $image = $image_data['file_path'];
                $public_path = 'blog/' . $image_data['file_name'];
            }

            $public = $collection->has('public') ? 1 : 0;
            

            if (isset($image)) {
                $merge = $collection->merge(compact('public', 'user_id', 'image', 'public_path'));
            } else {
                $merge = $collection->merge(compact('public', 'user_id'));
            }

            $blog->update($merge->all());

            return $blog;
        } catch (Exception $e) {
            Log::error('Error occurred while updating the article: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteBlog($id)
    {
        try {
            $blog = $this->findBlogById($id);

            if ($blog->user_id && $blog->user_id != Auth::user()->id) {
                throw new \Exception("Invalid user ID provided.");
            }

            if ($blog->image != null) {
                $this->deleteOne($blog->image);
            }

            $blog->delete();

            return $blog;
        } catch (Exception $e) {
            Log::error('Error occurred while deleting the article: ' . $e->getMessage());
            throw $e;
        }
    }
}