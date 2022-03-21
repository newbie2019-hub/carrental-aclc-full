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
        $cars = Car::with(['brand', 'user', 'user.info', 'branch'])->get();
        return $this->success('Cars has been retrieved successfully!', $cars);
    }

    public function store(Request $request) {
        $cars = Car::create([
            'model' => $request->model,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'transmission' => $request->transmission,
            'vehicle_identification_number' => $request->vehicle_identification_number,
            'plate_number' => $request->plate_number,
            'quantity' => $request->quantity,
        ]);

        return $this->success('Car has been created successfully!', $cars);
    }

    public function update(Request $request, $id) {
        $cars = Car::where('id', $id)->first();
        $cars->update([
            'model' => $request->model,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'transmission' => $request->transmission,
            'vehicle_identification_number' => $request->vehicle_identification_number,
            'plate_number' => $request->plate_number,
            'quantity' => $request->quantity,
        ]);

        return $this->success('Car has been updated successfully!', $cars);
    }

    public function destroy($id){
        Car::destroy($id);
        $cars = Car::onlyTrashed()->where('id', $id)->first();
        $cars->load(['user', 'user.info', 'branch', 'brand']);
        return $this->success('Car has been archived', $cars);
    }
}
