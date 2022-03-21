<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request){
        if($request->image){
            $fileName = 'image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/logo'), $fileName);

            return response()->json($fileName);
        }
    }
}
