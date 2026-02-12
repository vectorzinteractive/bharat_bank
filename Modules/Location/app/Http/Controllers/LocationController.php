<?php

namespace Modules\Location\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Location\Models\State;
use Modules\Location\Models\City;
use Modules\Location\Models\Town;
use Modules\Location\Models\Pincode;

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
