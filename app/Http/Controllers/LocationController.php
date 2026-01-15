<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\Town;
use App\Models\Pincode;

class LocationController extends Controller
{

    public function children(Request $request)
    {
        return match ($request->type) {

            'state' => City::where('state_id', $request->id)
                ->select('id', 'name as label')
                ->get(),

            'city' => Town::where('city_id', $request->id)
                ->select('id', 'name as label')
                ->get(),

            'town' => Pincode::where('town_id', $request->id)
                ->select('id', 'pincode as label')
                ->get(),

            default => response()->json([])
        };
    }


}
