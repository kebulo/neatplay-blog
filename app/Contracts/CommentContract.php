<?php

namespace App\Contracts;

interface CommentContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listComments(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findCommentById(int $id);

    /**
     * @param array $params
     * @return mixed
     */
    public function createComment(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateComment(array $params);

    /**
     * @param $id
     * @return bool
     */
    public function deleteComment($id);
}