<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $branch = Branch::with(['user', 'user.info'])->get();
        return $this->success('Branch has been retrieved successfully!', $branch);
    }

    public function store(Request $request) {
        $branch = Branch::create([
            'branch' => $request->branch,
            'description' => $request->description,
            'address' => $request->address,
            'user_id' => $request->user_id
        ]);

        return $this->success('Branch has been created successfully!', $branch);
    }

    public function update(Request $request, $id) {
        $branch = Branch::where('id', $id)->first();
        $branch->update([
            'branch' => $request->branch,
            'description' => $request->description,
            'address' => $request->address,
            'user_id' => $request->user_id
        ]);

        return $this->success('Branch has been created successfully!', $branch);
    }

    public function destroy($id){
        Branch::destroy($id);
        $branch = Branch::onlyTrashed()->where('id', $id)->first();
        $branch->load(['user', 'user.info']);
        return $this->success('Branch has been archived', $branch);
    }
}
