<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ArchivedUsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $users = User::onlyTrashed()->with(['info'])->where('id', '<>', auth()->user()->id)->get();
        return $this->success('User accounts has been retrieved successfully!', $users);
    }

    public function update(Request $request, $id){
        $user = User::withTrashed()->where('id', $id)->first();
        $user->restore();
        $user->info()->restore();
        $user->load(['info']);
        return $this->success('User account has been restored successfully', $user);
    }

    public function destroy($id){
        User::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('User account has been deleted permanently');
    }
}
