<?php
namespace App\Http\Controllers;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\OTPMail;
use App\Helper\JWTToken;
use App\Mail\PasswordMail;
use Illuminate\Http\Request;
use App\Http\Resources\UserDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function UserRegistration(Request $request){
        try {
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => Hash::make($request->input('password')),
            ]);
           return $this->success('User Registration Succesfull');
        } catch (Exception $e) {
            return $this->failed('User Registration Failed');
        }
    }

   public function UserLogin(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', '=', $email)->where('email_verified_at','!=',NULL)->where('otp',0)->first();
            if (!$user) {
                return $this->failed('User Not Found.');
            }
            if (!Hash::check($password, $user->password)) {
                return $this->failed('Password Not Match.');
            }
         
       if($user){
           // User Login-> JWT Token Issue
           $token=JWTToken::CreateToken($email,$user->id);
          
           return response()->json([
               'status' => 'success',
               'message' => 'User Login Successful',
               'Bearer Token'=>$token
           ],200);
       }
       else{
         return $this->failed('unauthorized');
       }

    }

   public function SendOTPCode(Request $request){

        $email=$request->input('email');
        $otp=rand(1000,9999);
        $count=User::where('email','=',$email)->count();

        if($count==1){
            try{
                 // OTP Email Address
                Mail::to($email)->send(new OTPMail($otp));
                // OTO Code Table Update
                User::where('email','=',$email)->update(['otp'=>$otp]);
                return $this->success('4 Digit OTP Code has been send to your email !');
            }catch (Exception $exception){
                return $this->failed('Something Went Wrong'); 
            }
           
        }
        else{
            return $this->failed('unauthorized');
        }
    }

   public function VerifyOTP(Request $request){

        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
            ->where('otp','=',$otp)->where('otp','>',0)->count();

        if($count==1){
            // Database OTP Update
            User::where('email','=',$email)->update(['otp'=>'0','email_verified_at'=>Carbon::now()]);

            return $this->success('Email Verification Successful');
           
        }
        else{
            return $this->failed('unauthorized');
        }
    }
   public function SendPassword(Request $request){

        $email=$request->input('email');
        $temp_password=$this->generateUniqueString();
        $count=User::where('email','=',$email)->count();

        if($count==1){

            try{
                // Password Send Email Address
                Mail::to($email)->send(new PasswordMail($temp_password));
                // Password Update
                User::where('email','=',$email)->update(['password'=>Hash::make($temp_password)]);
                return $this->success('New Password Code has been send to your email !');
            }catch (Exception $exception){
                return $this->failed('Something Went Wrong'); 
            }
        }
        else{
            return $this->failed('unauthorized');
        }

    }
    
    public function ResetPassword(Request $request){
        try{
            $email=JWTToken::GetEmail($request->bearerToken());
            $old_password=$request->input('old_password');
            $password=$request->input('password');
            $user = User::where('email', '=', $email)->where('email_verified_at','!=',NULL)->where('otp',0)->first();
            if (!$user) {
                return $this->failed('User Not Found.');
            }

            if (!Hash::check($old_password, $user->password)) {
                return $this->failed('Password Not Match.');
            }
     
            User::where('email','=',$email)->update(['password'=>Hash::make($password)]);
            return $this->success('Password Reset Successful.');

        }catch (Exception $exception){
            return $this->failed('Something Went Wrong'); 
        }
    }
   public function UserProfile(Request $request){
       
        $email=JWTToken::GetEmail($request->bearerToken());
        $user=User::where('email','=',$email)->first();
        return new UserDetails($user);
        
    }

   public function UpdateProfile(Request $request){
        try{
            $email=JWTToken::GetEmail($request->bearerToken());;
            $name=$request->input('name');
            $mobile=$request->input('mobile');
            User::where('email','=',$email)->update([
                'name'=>$name,
                'mobile'=>$mobile,
            ]);
            return $this->success('Update Request Successful');
        }catch (Exception $exception){
            return $this->failed('Something Went Wrong'); 
        }
    }
   public function generateUniqueString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[random_int(0, strlen($characters) - 1)];
        }
        
        return $string;
    }
}
