<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function success($message)
    {
        return response()->json([
            'result' => true,
            'message' => $message 
        ]);
    }

    public function failed($message)
    {
        return response()->json([
            'result' => false,
            'message' => $message 
        ]);
    }
}
