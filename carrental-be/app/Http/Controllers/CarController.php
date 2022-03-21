<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $cars = Car::with(['brand', 'user', 'user.info', 'branch', 'rate'])->get();
        return $this->success('Cars has been retrieved successfully!', $cars);
    }

    public function store(Request $request) {
        $cars = Car::create([
            'model' => $request->model,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'seats' => $request->seats,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'transmission' => $request->transmission,
            'vehicle_identification_number' => $request->vehicle_identification_number,
            'plate_number' => $request->plate_number,
            'quantity' => $request->quantity,
            'brand_id' => $request->brand_id,
            'branch_id' => $request->branch_id,
            'user_id' => auth()->user()->id
        ]);

        return $this->success('Car has been created successfully!', $cars);
    }

    public function update(Request $request, $id) {
        $cars = Car::where('id', $id)->first();
        $cars->update([
            'model' => $request->model,
            'year' => $request->year,
            'seats' => $request->seats,
            'mileage' => $request->mileage,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'transmission' => $request->transmission,
            'vehicle_identification_number' => $request->vehicle_identification_number,
            'plate_number' => $request->plate_number,
            'quantity' => $request->quantity,
            'brand_id' => $request->brand_id,
            'branch_id' => $request->branch_id,
            'user_id' => auth()->user()->id
        ]);

        return $this->success('Car has been updated successfully!', $cars);
    }

    public function destroy($id){
        Car::destroy($id);
        $cars = Car::onlyTrashed()->where('id', $id)->first();
        $cars->load(['user', 'user.info', 'branch', 'brand', 'rate']);
        return $this->success('Car has been archived', $cars);
    }
}
