<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CommentContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    protected $commentContract;
    protected $commentRepository;

    public function __construct(CommentContract $commentContract)
    {
        $this->commentRepository = $commentContract;
    }

    public function index()
    {
        $comments = $this->commentRepository->listComments();

        $this->setPageTitle('Comments', 'List of all comments');

        return view('admin.comment.index', compact('comments'));
    }

    public function create()
    {
        $this->setPageTitle('comment', 'Create comment');

        return view('admin.comment.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $params = $request->except('_token'); 

        $comment = $this->commentRepository->createComment($params);

        if (!$comment) {
            return $this->responseRedirectBack('Error occurred while creating the comment.', 'error', true, true);
        }

        return $this->responseRedirect('admin.comment.index', 'The comment was added successfully', 'success', false, false);
    }

    public function edit($id)
    {
        $comment = $this->commentRepository->findCommentById($id);

        $this->setPageTitle('comment', 'Edit comment : ' . $comment->title);

        return view('admin.comment.edit', compact('comment'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);   

        $params = $request->except('_token');

        $comment = $this->commentRepository->updateComment($params);  

        if (!$comment) {
            return $this->responseRedirectBack('Error occurred while updating the comment.', 'error', true, true);
        }

        return $this->responseRedirectBack('The comment was updated successfully', 'success', false, false);
    }

    public function delete($id)
    {
        $comment = $this->commentRepository->deleteComment($id);

        if (!$comment) {
            return $this->responseRedirectBack('Error occurred while deleting the comment.', 'error', true, true);
        }

        return $this->responseRedirect('admin.comment.index', 'The comment was deleted successfully', 'success', false, false);
    }
}
