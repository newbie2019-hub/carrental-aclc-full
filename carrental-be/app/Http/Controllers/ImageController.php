<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function storeLicense(Request $request){
        if($request->image){
            $fileName = 'image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $fileName);

            return response()->json($fileName);
        }
    }

    public function store(Request $request){
        if($request->image){
            $fileName = 'image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/logo'), $fileName);

            return response()->json($fileName);
        }
    }

    public function storeCar(Request $request){
        if($request->image){
            $fileName = 'image'.time().'.'.$request->image->extension();
            $request->image->move(public_path('images/cars'), $fileName);

            return response()->json($fileName);
        }
    }
}
