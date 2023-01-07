<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function show($username)
    {
        return new UserResource (User::where('username', $username)->select('username', 'created_at')->first());
    }
    
    public function getActivity($username)
    {
        return new UserResource (User::where('username', $username)->with('forums', 'forumComments')->first());
    }
}
