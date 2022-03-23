<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\RentalInfo;
use Exception;
use Illuminate\Http\Request;
use Stripe;

class RentalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $rental = Rental::with(['car','rental_info', 'user'])->latest()->get();
        return $this->success('Rental data has been retrieved successfully!', $rental);
    }

    public function store(Request $request){
        $response = '';
        if($request->payment_type == 'Credit Card'){
            try {
                $stripe = new \Stripe\StripeClient('sk_test_51KAQ3ADoJANsLvgsci2nG46EFVHLQbFxS6qfZ8UEpGl1ffYIVcDBsHOOKu7SVI9MBTCjBm43dSu8oKhQSSsK1uaF00ivv1r0U3');

                $token = $stripe->tokens->create([
                    'card' => [
                        'number' => $request->number,
                        'exp_month' => $request->exp_month,
                        'exp_year' => $request->exp_year,
                        'cvc' => $request->cvc,
                    ],
                ]);
        
                $stripecharge = new \Stripe\StripeClient('sk_test_51KAQ3ADoJANsLvgsci2nG46EFVHLQbFxS6qfZ8UEpGl1ffYIVcDBsHOOKu7SVI9MBTCjBm43dSu8oKhQSSsK1uaF00ivv1r0U3');
                $stripecharge->charges->create([
                        "amount" => $request->total * 100,
                        "currency" => "php",
                        "source" => $token,
                        "description" => "Renta Car payment for rental" 
                ]);
                
            }catch(Exception $e){
                return $this->error('Something went wrong!', $e->getMessage());
            }

            $data = [
                'pickup_date' => $request->pickup_date,
                'return_date' => $request->return_date,
                'additional_instruction' => $request->additional_instruction,
                'payment_type' => $request->payment_type,
                'payment_status' => 'Paid',
                'with_driver' => $request->with_driver,
                'driver_id' => $request->driver_id,
                'driver_payment' => $request->driver_payment,
                'drivers_license' => $request->drivers_license,
                'total_payment' => $request->total

            ];

            if($request->with_driver){
                $data['driver_id'] = $request->driver_id;
                $data['driver_payment'] = $request->driver_payment;
                $data['total_payment'] = $request->total_payment;
            }        

            $rentalInfo = RentalInfo::create($data);

            $rental = Rental::create([
                'car_id' => $request->car_id,
                'rentee_id' => auth()->user()->id,
                'rental_info_id' => $rentalInfo->id,
                'status' => 'On-going'
            ]);

            $rental->load(['user', 'rental_info']);

            return $this->success('Rental created successfully!', $rental);
        }
        else {
            $data = [
                'pickup_date' => $request->pickup_date,
                'return_date' => $request->return_date,
                'payment_type' => $request->payment_type,
                'additional_instruction' => $request->additional_instruction,
                'payment_status' => 'Pending',
                'with_driver' => $request->with_driver,
                'driver_id' => $request->driver_id,
                'driver_payment' => $request->driver_payment,
                'drivers_license' => $request->drivers_license,
                'total_payment' => $request->total
            ];

            if($request->with_driver){
                $data['driver_id'] = $request->driver_id;
                $data['driver_payment'] = $request->driver_payment;
                $data['total_payment'] = $request->total_payment;
            }      
            
            $rentalInfo = RentalInfo::create($data);

            $rental = Rental::create([
                'car_id' => $request->car_id,
                'rentee_id' => auth()->user()->id,
                'rental_info_id' => $rentalInfo->id,
                'status' => 'Pending'
            ]);

            $rental->load(['user', 'rental_info']);

            return $this->success('Please pay your rental transaction within 3 days or else it will be cancelled automatically', $rental);
        }
    }
}
