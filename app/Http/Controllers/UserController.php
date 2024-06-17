<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserSearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function searchByName(UserSearchRequest $request)
    {
        $users = User::where('name', 'like', '%' . $request->validated()['userName'] . '%')->where('id', '!=', Auth::id())->take(10)->get();
        return response()->json($users);
    }
}
