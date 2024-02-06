<?php

namespace App\Http\Controllers\Site;

use App\Contracts\CommentContract;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


/**
 * Comments Controller manages all the views and the bridge between the repository (database methods) and the public site requests
 */
class CommentsController extends BaseController
{
    protected $commentContract;
    protected $commentRepository;

    /**
     * CommentsController constructor.
     * @param CommentContract $commentContract -> Comments DB table handler
     */
    public function __construct(CommentContract $commentContract)
    {
        $this->commentRepository = $commentContract;
    }

    /**
     * Handle the request to create a new comment.
     * @param Request $request -> Comment data, only the content is required.
     * @return \Illuminate\Contracts\Routing\ResponseFactory -> Redirection with the message of success or error
     */
    public function store(Request $request)
    {
        try{
            $this->validate($request, [
                'content' => 'required | max:300',
            ]);

            $params = $request->except('_token');

            $comment = $this->commentRepository->createComment($params);

            if (!$comment) {
                return response()->json(['errors' => "Unable to create the comment, please try again later"], 500);
            }

            return response()->json(['message' => "The comment was saved successfuly", "data" => $comment, "success" => 200]);
        } catch (ValidationException $e) {
            return response()->json(['message' => "There was an error creating the comment, please ensure that the data is filled.", "data" => $comment, "error" => 500]);
        } catch (\Exception $e) {
            return response()->json(['message' => "Internal error. Please, try again later.", "data" => $comment, "success" => 500]);
        }
    }
}
