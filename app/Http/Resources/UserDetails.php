<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetails extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id'=>$this->user_id,
            'name' => $this->name,
            'email' => $this->email??'',
            'email_verified_at' => empty($this->email_verified_at)?'':date('d M, Y H:i:s',strtotime($this->email_verified_at)),
            'mobile' => $this->mobile??'',
            'otp' => $this->otp
        ];
    }
    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}
