<?php 
namespace App\Actions\Fortify\Responses;

use Laravel\Fortify\Contracts\LogoutResponse;

class CustomLogoutResponse implements LogoutResponse
{
    public function toResponse($request)
    {
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Logged out',
        //     // 'redirectUrl' => url('cms-admin')
        // ]);
        return redirect('cms-admin');

    }
}



?>