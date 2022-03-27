<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\RentalInfo;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $payment = Payment::create([
            'invoice' => 'Blank invoice',
            'rental_id' => $request->id,
            'total_payment' => $request->rental_info['total_payment'],
            'change' => $request->amount_tendered - $request->rental_info['total_payment'],
            'amount_tendered' => $request->amount_tendered
        ]);

        RentalInfo::where('id', $request->id)->update([
            'payment_status' => 'Paid'
        ]);

        $rental = Rental::where('id', $request->id)->first();
        $car = Car::where('id', $rental->car_id)->first();
        $car->update([
            'rental_status' => 'On-going'
        ]);
        
        return $this->success('Payment successful. The user may now get the car', $payment);
    }

    public function invoice($data){

    }
}
