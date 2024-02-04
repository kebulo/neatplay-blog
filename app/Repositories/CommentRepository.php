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
        return $this->model
            ->with('category')
            ->orderBy($order, $sort)
            ->select($columns)
            ->paginate($perPage);
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

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
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

        } catch (QueryException $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateComment(array $params)
    {
        $comment = $this->findCommentById($params['id']);

        $collection = collect($params)->except('_token');

        $comment->update($collection->all());

        return $comment;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteComment($id)
    {
        $comment = $this->findCommentById($id);

        $comment->delete();

        return $comment;
    }
}