<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerDetails;
use App\Http\Resources\CustomerCollection;

class CustomerController extends Controller
{

   public function CustomerList(Request $request){

        $user_id=JWTToken::GetID($request->bearerToken());
        $customers=Customer::where('user_id',$user_id)->get();
        return new CustomerCollection($customers);
    }   


   public function CustomerCreate(Request $request){
        $user_id=JWTToken::GetID($request->bearerToken());
        try {
            Customer::create([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'mobile'=>$request->input('mobile'),
                'user_id'=>$user_id
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Customer Registration Succesfull'
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'failed',
                'message' => 'Customer Registration Failed',
            ],200);
        }
    }


   


   public function CustomerDelete(Request $request){
        $customer_id=$request->input('id');
        $user_id=JWTToken::GetID($request->bearerToken());
       Customer::where('id',$customer_id)->where('user_id',$user_id)->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Customer Delete Succesfull'
        ],200);
    }


   public function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        $user_id=JWTToken::GetID($request->bearerToken());
        $customer = Customer::where('id',$customer_id)->where('user_id',$user_id)->first();
        return new CustomerDetails($customer);
    }


    public function CustomerUpdate(Request $request){
        $customer_id=$request->input('id');
        $user_id=JWTToken::GetID($request->bearerToken());
       Customer::where('id',$customer_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Customer Update Succesfull'
        ],200);
    }



}
