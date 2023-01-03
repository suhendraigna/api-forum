<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use App\Http\Controllers\AuthUserTrait;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    use AuthUserTrait;

    public function __construct()
    {
        return auth()->shouldUse('api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Forum::with('user:id,username')->paginate(3);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest();
        $user = $this->getAuthUser();
        $user->forums()->create([
            'title' => request('title'),
            'body' => request('body'),
            'slug' => Str::slug(request('title'), '-').'-'.time(),
            'category' => request('category'),
        ]);

        return response()->json([
            'message' => 'A new forum has been posted!',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Forum::with('user:id,username', 'comments.user:id,username')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validateRequest();
        $forum = Forum::find($id);
        $this->checkOwnership($forum->user_id);

        $forum->update([
            'title' => request('title'),
            'body' => request('body'),
            'category' => request('category'),
        ]);
        return response()->json([
            'message' => 'Forum has been updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $forum = Forum::find($id);
        $this->checkOwnership($forum->user_id);

        $forum->delete();
        return response()->json(['message' => 'Forum deleted!']);
    }

    private function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:2',
            'body' => 'required',
            'category' => 'required'
        ]);
        if($validator->fails()){
            response()->json($validator->messages())->send();
            exit;
        }
    }
}
