<?php
namespace App\Actions\Fortify\Responses;

use Laravel\Fortify\Contracts\PasswordResetResponse;

class CustomPasswordResetResponse implements PasswordResetResponse
{
    public function toResponse($request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successfully',
            'redirectUrl' => url('/login')
        ]);
    }
}



?>