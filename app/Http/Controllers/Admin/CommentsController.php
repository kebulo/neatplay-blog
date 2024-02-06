<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CommentContract;
use App\Http\Controllers\BaseController;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentsController extends BaseController
{
    protected $commentContract;
    protected $commentRepository;

    /**
     * CommentsController constructor.
     * @param CommentContract $commentContract -> Comment db table handler
     */
    public function __construct(CommentContract $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Get all the articles in the table and return the view with the data.
     * @param $request: Request -> Search param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $comments = $this->commentRepository->listComments();

            $this->setPageTitle('Comments', 'List of all comments');

            return view('admin.comment.index', compact('comments'));
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
     * Handle the request to delete a comment based on the ID provided
     * @param $id -> Comment ID.
     * @param $blog_id -> Blog ID.
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function delete($id, $blog_id)
    {
        try {
            $user = auth()->user();

            if (!$user->articles()->where('id', $blog_id)->exists()) {
                return redirect()->back()->with('error', 'Unauthorized Access');
            }

            $comment = $this->commentRepository->findCommentById($id);
            
            if (!$comment || $comment->blog_id != $blog_id) {
                return redirect()->back()->with('error', 'Comment not found or does not belong to the specified blog');
            }

            $comment = $this->commentRepository->deleteComment($id);

            if (!$comment) {
                return redirect()->back()->with('error', 'Error occurred while deleting the comment.');
            }

            return redirect()->back()->with('success', 'The comment was deleted successfully.');
        } catch (ValidationException $e) {
            return $this->responseRedirectBack('Validation error: ' . $e->getMessage(), 'error', true, true)->withInput();
        } catch (QueryException $e) {
            return $this->responseRedirectBack('Error occurred while deleting the comment: Database query error. Please check the data and try again.', 'error', true, true)->withInput();
        } catch (ModelNotFoundException $e) {
            return $this->responseRedirectBack('Error occurred while deleting the comment: Could not find the resource', 'error', true, true)->withInput();
        } catch (\Exception $e) {
            return $this->responseRedirectBack('Internal error. Please, try again later.', 'error', true, true)->withInput();
        }
    }
}
