<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\CommentContract;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentsController extends Controller
{
    protected $commentContract;
    protected $commentRepository;

    /**
     * CommentsController constructor.
     * @param CommentContract $commentContract -> Comment db table handler
     */
    public function __construct(CommentContract $commentContract)
    {
        $this->commentRepository = $commentContract;
    }

    /**
     * Get all the articles in the table and return the view with the data.
     * @param $request: Request -> Search param
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse -> Redirection with the message of success or error
     */
    public function delete($id)
    {
        try {
            $comment = $this->commentRepository->deleteComment($id);

            if (!$comment) {
                return $this->responseRedirectBack('Error occurred while deleting the comment.', 'error', true, true);
            }

            return $this->responseRedirect('admin.comment.index', 'The comment was deleted successfully', 'success', false, false);
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
