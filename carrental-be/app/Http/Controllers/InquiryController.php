<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['store']]);
    }

    
    public function index(){
        $inquiry = Inquiry::latest()->get();
        return $this->success('Data retrieved successfully!', $inquiry);
    }

    public function store(Request $request){
        Inquiry::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return $this->success('Message sent successfully!');
    }

    public function destroy($id){
        Inquiry::destroy($id);
        return $this->success('User inquiry has been deleted permanently');
    }
}
