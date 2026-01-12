<?php 
namespace App\Actions\Fortify\Responses;

use Laravel\Fortify\Contracts\LoginResponse;

class CustomLoginResponse implements LoginResponse
{
    public function toResponse($request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'redirectUrl' => url('cms-admin/dashboard')
        ]);
    }
}


?>