<?php

namespace App\Repositories;

use App\Contracts\CommentContract;
use App\Models\Comment;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class CommentRepository extends BaseRepository implements CommentContract
{
    /**
     * CommentRepository constructor.
     * @param Comment $model
     */
    public function __construct(Comment $model)
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
    public function listComments(string $order = 'id', string $sort = 'desc', array $columns = ['*'], int $perPage = 10)
    {
        try {
            return $this->model
                ->with('blogs')
                ->orderBy($order, $sort)
                ->select($columns)
                ->paginate($perPage);
        } catch (Exception $e) {
            Log::error('Error occurred while searching the comments: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findCommentById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (Exception $e) {
            Log::error('Error occurred while searching the comment based on the ID: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param array $params
     * @return Comment | mixed
     */
    public function createComment(array $params)
    {
        try {
            $collection = collect($params);

            $comment = new Comment($collection->all());

            $comment->save();

            return $comment;

        } catch (Exception $e) {
            Log::error('Error occurred while creating the comment: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteComment($id)
    {
        try {
            $comment = $this->findCommentById($id);

            $comment->delete();

            return $comment;
        } catch (Exception $e) {
            Log::error('Error occurred while deleting the comment based on the ID: ' . $e->getMessage());
            throw $e;
        }
    }
}