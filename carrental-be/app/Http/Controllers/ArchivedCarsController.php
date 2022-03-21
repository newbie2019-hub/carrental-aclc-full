<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class ArchivedCarsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $cars = Car::onlyTrashed()->with(['user', 'user.info'])->get();
        return $this->success('Cars has been retrieved successfully!', $cars);
    }

    public function update(Request $request, $id){
        $cars = Car::withTrashed()->where('id', $id)->first();
        $cars->restore();
        $cars->load(['user', 'user.info']);
        return $this->success('Car has been restored successfully', $cars);
    }

    public function destroy($id){
        Car::onlyTrashed()->where('id', $id)->forceDelete();
        return $this->success('Car has been permanently');
    }
}
