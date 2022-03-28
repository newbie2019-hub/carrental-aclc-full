<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\Request;

class ArchivedRentalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $rental = Rental::onlyTrashed()->with(['car:id,rental_status,car_brand_id,model,plate_number,year,seats,vehicle_identification_number', 'car.brand:id,brand,logo', 'rental_info', 'user.info'])->latest()->get();
        return $this->success('Rental data has been retrieved successfully!', $rental);
    }
}
