<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($username)
    {
        return User::where('username', $username)->select('username', 'created_at')->first();
    }
}
