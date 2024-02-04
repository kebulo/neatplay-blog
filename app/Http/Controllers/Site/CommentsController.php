<?php

namespace App\Http\Controllers\Site;

use App\Contracts\CommentContract;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentsController extends BaseController
{
    protected $commentContract;
    protected $commentRepository;

    public function __construct(CommentContract $commentContract)
    {
        $this->commentRepository = $commentContract;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required | max:300',
        ]);

        $params = $request->except('_token');

        $comment = $this->commentRepository->createComment($params);

        if (!$comment) {
            return response()->json(['errors' => "Unable to create the comment, please try again later"], 500);
        }

        return response()->json(['message' => "The comment was saved successfuly", "data" => $comment, "success" => 200]);
    }
}
