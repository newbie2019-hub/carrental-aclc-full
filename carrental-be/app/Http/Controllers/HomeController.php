<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Car;
use App\Models\CarBrand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index(){

        $branch = Branch::latest()->get(['id', 'branch']);
        $cars = Car::where('for_rent_status', 'Approved')->with(['rate', 'branch', 'brand', 'user.info'])->latest()->take(15)->get();
        $brands = CarBrand::latest()->get(['id', 'brand']);

        $data = array();
        $data['id'] = 0;
        $data['branch'] = 'All Branch';

        $branch->put(count($branch) ,$data);
        return response()->json([
            'msg' => 'Data retrieved successfully!',
            'branch' => $branch,
            'cars' => $cars,
            'brands' => $brands,
        ]);
    }
}
