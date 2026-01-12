<?php 
namespace App\Actions\Fortify\Responses;

use Laravel\Fortify\Contracts\RegisterResponse;

class CustomRegisterResponse implements RegisterResponse
{
    public function toResponse($request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful',
            'redirectUrl' => url('cms-admin/dashboard')
        ]);
    }
}



?>