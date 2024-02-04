<?php

namespace App\Contracts;

interface BlogsContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listBlogs(string $search = null, string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listHomeBlogs(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    /**
     * @param int $id
     * @return mixed
     */
    public function findBlogById(int $id);

    /**
     * @param array $params
     * @return mixed
     */
    public function createBlog(array $params);

    /**
     * @param array $params
     * @return mixed
     */
    public function updateBlog(array $params);

    /**
     * @param $id
     * @return bool
     */
    public function deleteBlog($id);
}