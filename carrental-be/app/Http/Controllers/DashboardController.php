<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Car;
use App\Models\CarBrand;
use App\Models\Inquiry;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $branch = Branch::count();
        $brand = CarBrand::count();

        if(auth()->user()->info->role->role == 'Admin'){
            $inquiry = Inquiry::count();
            $cars = Car::count();
            $users = User::count();
            $rents = Rental::whereRelation('car', 'rental_status', '<>', 'Available')->count();
            $rentals = Rental::with(['car:id,rental_status,car_brand_id,model,plate_number,year,seats,vehicle_identification_number', 'car.brand:id,brand,logo', 'rental_info', 'user.info', 'payment'])->latest()->take(5)->get();
            return response()->json([
                'cars' => $cars,
                'brand' => $brand,
                'branch' => $branch,
                'inquiry' => $inquiry,
                'users' => $users,
                'rents' => $rents,
                'latest_transactions' => $rentals
            ]);
        }
        else {
            $cars = Car::where('user_id', auth()->user()->id)->count();
            $rentals = Rental::whereRelation('car', 'user_id', auth()->user()->id)->with(['car:id,car_brand_id,model,plate_number,year,seats,vehicle_identification_number', 'car.brand:id,brand,logo', 'rental_info', 'user.info', 'payment'])->latest()->take(5)->get();
            $rents = Rental::whereRelation('car', 'user_id', auth()->user()->id)->whereRelation('car', 'rental_status', '<>', 'Available')->count();
            return response()->json([
                'cars' => $cars,
                'branch' => $branch,
                'brand' => $brand,
                // 'inquiry' => $inquiry,
                // 'users' => $users,
                'rents' => $rents,
                'latest_transactions' => $rentals
            ]);
        }
    }
}
