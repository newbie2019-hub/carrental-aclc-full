<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class ArchivedBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $branch = Branch::onlyTrashed()->with(['user', 'user.info'])->get();
        return $this->success('User accounts has been retrieved successfully!', $branch);
    }

    public function update(Request $request, $id){
        $branch = Branch::withTrashed()->where('id', $id)->first();
        $branch->restore();
        $branch->load(['user', 'user.info']);
        return $this->success('Branch has been restored successfully', $branch);
    }

    public function destroy($id){
        Branch::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Branch has been permanently');
    }
}
