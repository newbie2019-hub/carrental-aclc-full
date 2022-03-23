<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class ArchivedDriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $brand = Driver::onlyTrashed()->get();
        return $this->success('Drivers data has been retrieved successfully!', $brand);
    }

    public function update(Request $request, $id){
        $brand = Driver::withTrashed()->where('id', $id)->first();
        $brand->restore();
        return $this->success('Driver data has been restored successfully', $brand);
    }

    public function destroy($id){
        Driver::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Driver\'s data has been permanently');
    }
}
