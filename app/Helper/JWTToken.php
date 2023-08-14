<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JWTToken
{

    public static function CreateToken($userEmail,$userID):string{
        $key =env('JWT_KEY');
        $payload=[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60*30,
            'userEmail'=>$userEmail,
            'userID'=>$userID
        ];
       return JWT::encode($payload,$key,'HS256');
    }

    public static function VerifyToken($token):string|object
    {
        try {
            if($token==null){
                return 'unauthorized';
            }
            else{
                $key =env('JWT_KEY');
                $decode=JWT::decode($token,new Key($key,'HS256'));
                return $decode;
            }
        }
        catch (Exception $e){
            return 'unauthorized';
        }
    }

    public static function GetEmail($token){

        $result=JWTToken::VerifyToken($token);
        if($result=="unauthorized"){
            return '';
        }else{
            return $result->userEmail;
        }

    }
    public static function GetID($token){

        $result=JWTToken::VerifyToken($token);
        if($result=="unauthorized"){
            return '';
        }else{
            return $result->userID;
        }

    }
}
