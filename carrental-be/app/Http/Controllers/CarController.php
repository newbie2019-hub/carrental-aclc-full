<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarRate;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        if(auth()->user()->info->role->role == 'Admin'){
            $cars = Car::with(['brand', 'user', 'user.info', 'branch', 'rate'])->latest()->get();
            return $this->success('Cars has been retrieved successfully!', $cars);
        }
        else {
            $cars = Car::with(['brand', 'user', 'user.info', 'branch', 'rate'])->where('user_id', auth()->user()->id)->latest()->get();
            return $this->success('Not Admin', $cars);
        }
    }

    public function store(Request $request) {

        $carrate = CarRate::create([
            'per_day' => $request->rate['per_day'],
            'per_week' => $request->rate['per_week'],
            'per_month' => $request->rate['per_month'],
            'with_driver' => $request->rate['with_driver']
        ]);

        $status = auth()->user()->info->role->role == 'Admin' ? 'Approved' : 'Pending';

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
            'car_brand_id' => $request->car_brand_id,
            'branch_id' => $request->branch_id,
            'fuel_type' => $request->fuel_type,
            'user_id' => auth()->user()->id,
            'image' => $request->image,
            'car_rate_id' => $carrate->id,
            'for_rent_status' => $status
        ]);

        $cars->load(['user', 'user.info', 'branch', 'brand', 'rate']);

        return $this->success('Car has been created successfully!', $cars);
    }

    public function update(Request $request, $id) {
        $cars = Car::where('id', $id)->first();

        $data = [
            'model' => $request->model,
            'year' => $request->year,
            'mileage' => $request->mileage,
            'seats' => $request->seats,
            'description' => $request->description,
            'remarks' => $request->remarks,
            'transmission' => $request->transmission,
            'vehicle_identification_number' => $request->vehicle_identification_number,
            'plate_number' => $request->plate_number,
            'car_brand_id' => $request->car_brand_id,
            'branch_id' => $request->branch_id,
            'user_id' => auth()->user()->id,
            'fuel_type' => $request->fuel_type,
        ];

        if($request->image){
            $data['image'] = $request->image;
        }

        $cars->update($data);

        $updatedCar = Car::with(['brand', 'user', 'user.info', 'branch', 'rate'])->where('id', $id)->first();
        
        return $this->success('Car has been updated successfully!', $updatedCar);
    }

    public function destroy($id){
        Car::destroy($id);
        $cars = Car::onlyTrashed()->where('id', $id)->first();
        $cars->load(['user', 'user.info', 'branch', 'brand', 'rate']);
        return $this->success('Car has been archived', $cars);
    }
}
