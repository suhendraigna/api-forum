<?php

namespace App\Http\Controllers;

use App\Models\ForumComment;

use App\Http\Controllers\AuthUserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ForumCommentController extends Controller
{
    use AuthUserTrait;
    
    public function __construct()
    {
        return auth()->shouldUse('api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $forumId)
    {
        $this->validateRequest();
        $user = $this->getAuthUser();

        $user->forumComments()->create([
            'body' => request('body'),
            'forum_id' => $forumId
        ]);

        return response()->json([
            'message' => 'A new comment successfully posted!',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $forumId,$commentId)
    {
        $this->validateRequest();
        $comment = ForumComment::find($commentId);
        
        $this->checkOwnership($comment->user_id);

        $comment->update([
            'body' => request('body')
        ]);
        return response()->json([
            'message' => 'Comment successfully updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($forumId, $commentId)
    {
        $comment = ForumComment::find($commentId);
        $this->checkOwnership($comment->user_id);

        $comment->delete();
        return response()->json(['message' => 'Comment deleted!']);
    }

     private function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
            'body' => 'required',
        ]);
        if($validator->fails()){
            response()->json($validator->messages())->send();
            exit;
        }
    }
}
