<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(){
        $drivers = Driver::latest()->get();
        return $this->success('Drivers has been retrieved successfully!', $drivers);
    }

    public function store(Request $request) {
        $driver = Driver::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'profile_img' => $request->profile_img_uploaded,
            'valid_id' => $request->valid_id_uploaded,
        ]);

        return $this->success('Driver has been added successfully!', $driver);
    }

    public function update(Request $request, $id) {
        $drivers = Driver::where('id', $id)->first();
        $drivers->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'profile_img' => $request->profile_img_uploaded,
            'valid_id' => $request->valid_id_uploaded,
        ]);

        return $this->success('Driver has been added successfully!', $drivers);
    }

    public function destroy($id){
        Driver::destroy($id);
        $drivers = Driver::onlyTrashed()->where('id', $id)->first();
        return $this->success('Driver has been archived', $drivers);
    }
}
