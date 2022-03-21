<?php

namespace App\Http\Controllers;

use App\Models\CarBrand;
use Illuminate\Http\Request;

class ArchivedBrandsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $brand = CarBrand::onlyTrashed()->get();
        return $this->success('Car brands has been retrieved successfully!', $brand);
    }

    public function update(Request $request, $id){
        $brand = CarBrand::withTrashed()->where('id', $id)->first();
        $brand->restore();
        return $this->success('Brand has been restored successfully', $brand);
    }

    public function destroy($id){
        CarBrand::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Brand has been permanently');
    }
}
