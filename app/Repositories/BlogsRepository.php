<?php

namespace App\Repositories;

use App\Models\Blog;
use App\Contracts\BlogsContract;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

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
    public function listBlogs(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findBlogById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
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
            $user_id = auth()->id();

            $merge = $collection->merge(compact('public', 'user_id'));

            $Blog = new Blog($merge->all());

            $Blog->save();

            return $Blog;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBlog(array $params)
    {
        $blog = $this->findBlogById($params['id']);

        $collection = collect($params)->except('_token');

        $image = '';
        $public_path = '';

        if ($collection->has('image') && ($params['image'] instanceof UploadedFile)) {

            if ($blog->image != null) {
                $this->deleteOne($blog->image);
            }

            $image_data = $this->uploadOne($params['image'], '/blog');

            $image = $image_data['file_path'];
            $public_path = 'blog/'.$image_data['file_name'];
        }

        $public = $collection->has('public') ? 1 : 0;
        $user_id = auth()->id();

        $merge = $collection->merge(compact('public', 'user_id', 'image', 'public_path'));

        $blog->update($merge->all());

        return $blog;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteBlog($id)
    {
        $Blog = $this->findBlogById($id);

        if ($Blog->image != null) {
            $this->deleteOne($Blog->image);
        }

        $Blog->delete();

        return $Blog;
    }
}