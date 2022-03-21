<?php

namespace App\Http\Controllers;

use App\Models\CarBrand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $brands = CarBrand::withCount(['cars'])->latest()->get(['id','logo','brand','created_at']);
        return $this->success('Brands has been retrieved successfully!', $brands);
    }

    public function store(Request $request) {
        $brands = CarBrand::create([
            'brand' => $request->brand,
            'logo' => $request->logo
        ]);

        return $this->success('Car Brand has been created successfully!', $brands);
    }

    public function update(Request $request, $id) {
        $brands = CarBrand::withCount(['cars'])->where('id', $id)->first();
        $brands->update([
            'brand' => $request->brand,
            'logo' => $request->logo
        ]);

        return $this->success('Brands has been created successfully!', $brands);
    }

    public function destroy($id){
        CarBrand::destroy($id);
        $brands = CarBrand::onlyTrashed()->withCount(['cars'])->where('id', $id)->first();
        return $this->success('Brand has been archived', $brands);
    }
}
