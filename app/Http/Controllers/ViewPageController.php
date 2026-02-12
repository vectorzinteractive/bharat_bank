<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Auction\Models\Auction;
use Modules\UnclaimedDeposit\Models\UnclaimedDeposit;
use Modules\Location\Models\State;
use Modules\Location\Models\City;
use Modules\Location\Models\Town;
use Modules\Location\Models\Pincode;

class ViewPageController extends Controller
{
public function auctions(Request $request)
{
    $filterMap = [
        'search' => [
            'columns' => [
                ['column' => 'description', 'operator' => 'like'],
                ['relation' => 'pincode.town.city.state', 'column' => 'name', 'operator' => 'like'],
                ['relation' => 'pincode.town.city', 'column' => 'name', 'operator' => 'like'],
                ['relation' => 'pincode.town', 'column' => 'name', 'operator' => 'like'],
            ],
        ],
        'state' => [
            'relation' => 'pincode.town.city.state',
            'column' => 'id',
        ],
        'city' => [
            'relation' => 'pincode.town.city',
            'column' => 'id',
        ],
        'town' => [
            'relation' => 'pincode.town',
            'column' => 'id',
        ],
        'price_min' => [
            'column' => 'price',
            'operator' => '>=',
        ],
        'price_max' => [
            'column' => 'price',
            'operator' => '<=',
        ],
        'sqft_min' => [
            'column' => 'sq_ft',
            'operator' => '>=',
        ],
        'sqft_max' => [
            'column' => 'sq_ft',
            'operator' => '<=',
        ],
    ];

    $data = Auction::with('pincode.town.city.state')
        ->filterByParams($request, $filterMap)
        ->latest()
        ->get();

    if ($request->ajax()) {
        return view('auction-data', compact('data'))->render();
    }

    $states = State::all();
    $cities = City::all();
    $towns  = Pincode::with('town')->get()->pluck('town')->unique('id');

    return view('auctions-list', compact('data', 'states', 'cities', 'towns'));
}
public function unclaimedDeposit(Request $request)
{
    $filterMap = [
        'search' => [
            'columns' => [
                ['column' => 'description'],
                ['column' => 'name'],
                ['column' => 'udrn_id'],
                ['column' => 'name', 'relation' => 'pincode.town'],
                ['column' => 'name', 'relation' => 'pincode.town.city'],
                ['column' => 'name', 'relation' => 'pincode.town.city.state']
            ]
        ],
        'state' => [
            'relation' => 'pincode.town.city.state',
            'column' => 'id'
        ],
        'city' => [
            'relation' => 'pincode.town.city',
            'column' => 'id'
        ],
        'town' => [
            'relation' => 'pincode.town',
            'column' => 'id'
        ]
    ];

    $data = UnclaimedDeposit::with('pincode.town.city.state')
            ->filterByParams($request, $filterMap)
            ->latest()
            ->get();

    if ($request->ajax()) return view('unclaimed-deposit-data', compact('data'))->render();

    return view('unclaimed-deposit-list', [
        'data'   => $data,
        'states' => State::all(),
        'cities' => City::all(),
        'towns'  => Town::all(),
    ]);
}



}
