<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $users = User::withCount(['cars'])->with(['info'])->where('id', '<>', auth()->user()->id)->latest()->get();
        return $this->success('User accounts has been retrieved successfully!', $users);
    }

    public function destroy($id){
        User::destroy($id);
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->load(['info']);
        return $this->success('User account has been archived', $user);
    }
}
