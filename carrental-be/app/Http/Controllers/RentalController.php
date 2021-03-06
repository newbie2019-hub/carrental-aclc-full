<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\RentalInfo;
use Exception;
use LaravelDaily\Invoices\Classes\Party;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Stripe;

class RentalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        if(auth()->user()->info->role->role == 'Admin'){
            $rental = Rental::with(['car:id,rental_status,car_brand_id,model,plate_number,year,seats,vehicle_identification_number', 'car.brand:id,brand,logo', 'rental_info', 'user.info', 'payment'])->latest()->get();
            return $this->success('Rental data has been retrieved successfully!', $rental);
        }
        else {
            $rental = Rental::whereRelation('car', 'user_id', auth()->user()->id)->with(['car:id,rental_status,car_brand_id,model,plate_number,year,seats,vehicle_identification_number', 'car.brand:id,brand,logo', 'rental_info', 'user.info', 'payment'])->latest()->get();
            return $this->success('Rental data has been retrieved successfully!', $rental);
        }
    }

    public function markFinished($id){
        $rental = Rental::where('id', $id)->first();
        $rental->update([
            'status' => 'Finished'
        ]);

        $rental->load(['car']);

        $rental->car->update([
            'rental_status' => 'Available'
        ]);

        return $this->success('Rental has been marked as finished successfully!', $rental);
    }

    public function store(Request $request){

        $car = Car::where('id', $request->car_id)->first();

        if($car->user_id == auth()->user()->id){
            return $this->error('You cannot rent your own car');
        }

        $rental = Rental::with(['rental_info'])->where('car_id', $request->car_id)->where(function ($query){
            $query->where('status', 'Pending')
            ->orWhere('status', 'On-going');
        })->first();

        if($car->rental_status != 'Available'){
            return $this->error('This car is not available for rent. ETA: ' . $rental->rental_info->return_date);
        }

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
                $response = $stripecharge->charges->create([
                        "amount" => ($request->total + 5000)  * 100,
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
                'total_payment' => (($request->total - $request->driver_payment) * .12)  + $request->total + 5000,
            ];

            if($request->with_driver){
                $data['driver_id'] = $request->driver_id;
                $data['driver_payment'] = $request->driver_payment;
                $data['days_with_driver'] = $request->days_with_driver;
            }        

            $rentalInfo = RentalInfo::create($data);

            $rental = Rental::create([
                'transaction_number' => auth()->user()->id,
                'car_id' => $request->car_id,
                'rentee_id' => auth()->user()->id,
                'rental_info_id' => $rentalInfo->id,
                'invoice' => $response->receipt_url,
                'status' => 'On-going'
            ]);

            $rental->load(['user', 'rental_info']);

            $car = Car::where('id', $request->car_id)->first();
            if($car){
                $car->update([
                    'rental_status' => 'Rented'
                ]);
            }

            return response()->json(['msg' => 'Payment for rental is successful! You may check your account for the status', 'response' => $response]);
        }
        else {
            $data = [
                'pickup_date' => $request->pickup_date,
                'return_date' => $request->return_date,
                'payment_type' => $request->payment_type,
                'additional_instruction' => $request->additional_instruction,
                'payment_status' => 'Pending',
                'with_driver' => $request->with_driver,
                'drivers_license' => $request->drivers_license,
                'total_payment' => (($request->total - $request->driver_payment) * .12)  + $request->total + 5000
            ];

            if($request->with_driver){
                $data['driver_id'] = $request->driver_id;
                $data['driver_payment'] = $request->driver_payment;
                $data['days_with_driver'] = $request->days_with_driver;
            }      
            
            $rentalInfo = RentalInfo::create($data);

            $rental = Rental::create([
                'transaction_number' => auth()->user()->id,
                'car_id' => $request->car_id,
                'rentee_id' => auth()->user()->id,
                'rental_info_id' => $rentalInfo->id,
                'invoice' => $this->rentalInvoice($request),
                'status' => 'Pending'
            ]);

            $rental->load(['user', 'rental_info', 'car']);

            
            $car = Car::where('id', $request->car_id)->first();
            if($car){
                $car->update([
                    'rental_status' => 'Reserved'
                ]);
            }
            return $this->success('You can proceed to your account to check for your rental', $rental);
        }
    }

    public function rentalInvoice($data){
        $car = Car::with(['brand','user','user.info'])->where('id', $data->car_id)->first();

        $client = new Party([
            'name'  => $car->user->info->last_name . ', ' . $car->user->info->first_name . ' ' . $car->user->info->middle_name,
            'phone' => $car->user->info->contact_number,
            'custom_fields' => [
                'email' => $car->user->email,
                'address' => $car->user->info->address,
            ],
        ]);
        
        $customer = new Buyer([
            'name'          => auth()->user()->info->last_name . ', ' .auth()->user()->info->first_name . ' ' . auth()->user()->info->middle_name,
            'custom_fields' => [
                'email' => auth()->user()->email,
                'contact' => auth()->user()->info->contact_number,
                'address' => auth()->user()->info->address
            ],
        ]);

        $item = (new InvoiceItem())
        ->title($car->brand->brand . ' ' . $car->model)
        ->quantity($data->totalDays)
        ->taxByPercent(12)
        ->pricePerUnit(($data->total - $data->driver_payment) / $data->totalDays);

        $securityDeposit = (new InvoiceItem())
        ->title('Security Deposit')
        ->subTotalPrice(5000)
        ->pricePerUnit(5000);
        
        $driver = '';
        if($data->with_driver){
            $driver = (new InvoiceItem())
            ->title('Rental with driver')
            ->quantity($data->days_with_driver)
            ->pricePerUnit($data->driver_payment / $data->days_with_driver);
        }

        // $notes = [
        //     'Please pay on or before the specified date.',
        //     'If you were not able to pay your transaction will be cancelled',
        //     'Thank You!',
        // ];
        // $notes = implode("<br>", $notes);
        
        if($data->with_driver){
            $invoice = Invoice::make()
                ->buyer($customer)
                ->seller($client)
                ->currencySymbol('???')
                ->currencyCode('PHP')
                ->currencyFormat('{SYMBOL}{VALUE}')
                ->addItem($item)
                ->addItem($driver)
                ->addItem($securityDeposit)
                ->save('invoice');
        }
        else {
            $invoice = Invoice::make()
                ->buyer($customer)
                ->seller($client)
                ->currencySymbol('???')
                ->currencyCode('PHP')
                ->currencyFormat('{SYMBOL}{VALUE}')
                ->addItem($item)
                ->save('invoice');
        }

        $link = $invoice->url();
        return $link;
    }

    public function destroy($id){
        Rental::destroy($id);
        $rental = Rental::onlyTrashed()->with(['brand', 'car', 'user', 'user.info', 'rental_info'])->where('id', $id)->first();
        $rental->update([
            'status' => 'Cancelled'
        ]);
        $rental->car->update([
            'rental_status' => 'Available'
        ]);
        return $this->success('Rental transaction has been archived', $rental);
    }
}
