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
        $customers=Customer::where('user_id',$user_id)->paginate(10);
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
            return $this->success('Customer Registration Succesfull');
          
        }catch(\Exception $e){
            return $this->failed('Customer Registration Failed'); 
           
        }
    }

   public function CustomerByID(Request $request){
        $customer_id=$request->input('id');
        $user_id=JWTToken::GetID($request->bearerToken());
        try{
            $customer_user_id= Customer::findOrFail($customer_id)->user_id;
            if($customer_user_id!==$user_id){
                return $this->failed('Customer Not Yours.');
            }
        }catch (\Exception $exception){
            return $this->failed('Something Went Wrong'); 
        }
        $customer = Customer::where('id',$customer_id)->where('user_id',$user_id)->first();
        return new CustomerDetails($customer);
    }


    public function CustomerUpdate(Request $request){
        $customer_id=$request->input('id');
        $user_id=JWTToken::GetID($request->bearerToken());
        try{
            $customer_user_id= Customer::findOrFail($customer_id)->user_id;
            if($customer_user_id!==$user_id){
                return $this->failed('Customer Not Yours.');
            }
        }catch (\Exception $exception){
            return $this->failed('Something Went Wrong'); 
        }
        
       Customer::where('id',$customer_id)->where('user_id',$user_id)->update([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
        ]);
        return $this->success('Customer Update Succesfull');
       
    }

    public function CustomerDelete(Request $request){
        $customer_id=$request->input('id');
        $user_id=JWTToken::GetID($request->bearerToken());
        try{
            $customer_user_id= Customer::findOrFail($customer_id)->user_id;
            if($customer_user_id!==$user_id){
                return $this->failed('Customer Not Yours.');
            }
        }catch (\Exception $exception){
            return $this->failed('Something Went Wrong'); 
        }
       
       Customer::where('id',$customer_id)->where('user_id',$user_id)->delete();
       return $this->success('Customer Delete Succesfull');
       
    }



}
