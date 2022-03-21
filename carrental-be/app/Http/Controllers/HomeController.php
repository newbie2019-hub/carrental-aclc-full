<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Car;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index(){
        $branch = Branch::latest()->get(['id', 'branch']);
        $cars = Car::latest()->get();
        return response()->json([
            'msg' => 'Data retrieved successfully!',
            'branch' => $branch,
            'cars' => $cars
        ]);
    }
}
