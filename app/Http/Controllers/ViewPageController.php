<?php

namespace App\Http\Controllers;

use App\Models\UnclaimedDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Auction;
use App\Models\State;
use App\Models\City;
use App\Models\Town;
use App\Models\AuctionCity;
use App\Models\Pincode;

class ViewPageController extends Controller
{
//     public function auctions(Request $request)
//     {
//         $query = Auction::with('pincode.town.city.state');

//     if ($request->filled('search')) {
//         $search = $request->search;
//         $query->where(function($q) use ($search) {
//             $q->where('description', 'like', "%$search%")
//               ->orWhereHas('pincode.town.city.state', fn($q2) => $q2->where('name', 'like', "%$search%"))
//               ->orWhereHas('pincode.town.city', fn($q2) => $q2->where('name', 'like', "%$search%"))
//               ->orWhereHas('pincode.town', fn($q2) => $q2->where('name', 'like', "%$search%"));
//         });
//     }

//     if ($request->filled('state')) {
//     $states = is_array($request->state) ? $request->state : [$request->state];
//     $query->whereHas('pincode.town.city.state', fn($q) => $q->whereIn('id', $states));
// }

// if ($request->filled('city')) {
//     $cities = is_array($request->city) ? $request->city : [$request->city];
//     $query->whereHas('pincode.town.city', fn($q) => $q->whereIn('id', $cities));
// }

// if ($request->filled('town')) {
//     $towns = is_array($request->town) ? $request->town : [$request->town];
//     $query->whereHas('pincode.town', fn($q) => $q->whereIn('id', $towns));
// }

//     if ($request->filled('price_min')) $query->where('price', '>=', $request->price_min);
//     if ($request->filled('price_max')) $query->where('price', '<=', $request->price_max);
//     if ($request->filled('sqft_min')) $query->where('sq_ft', '>=', $request->sqft_min);
//     if ($request->filled('sqft_max')) $query->where('sq_ft', '<=', $request->sqft_max);

//     $auctions = $query->latest()->get();

//     if ($request->ajax()) return view('auction-data', compact('auctions'))->render();

//     $states = State::all();
//     $cities = City::all();
//     $towns  = Pincode::with('town')->get()->pluck('town')->unique('id');

//     return view('auctions-list', compact('auctions','states','cities','towns'));
//     }

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


//     public function unclaimedDeposit(Request $request)
//     {
//         $query = UnclaimedDeposit::with('pincode.town.city.state');

//     if ($request->filled('search')) {
//     $search = $request->search;

//     $query->where(function ($q) use ($search) {
//         $q->where('description', 'like', "%{$search}%")
//           ->orWhere('name', 'like', "%{$search}%")
//           ->orWhereHas('pincode.town.city.state', function ($q2) use ($search) {
//               $q2->where('name', 'like', "%{$search}%");
//           })
//           ->orWhereHas('pincode.town.city', function ($q2) use ($search) {
//               $q2->where('name', 'like', "%{$search}%");
//           })
//           ->orWhereHas('pincode.town', function ($q2) use ($search) {
//               $q2->where('name', 'like', "%{$search}%");
//           });
//     });
// }

//     if ($request->filled('state')) {
//     $states = is_array($request->state) ? $request->state : [$request->state];
//     $query->whereHas('pincode.town.city.state', fn($q) => $q->whereIn('id', $states));
// }

// if ($request->filled('city')) {
//     $cities = is_array($request->city) ? $request->city : [$request->city];
//     $query->whereHas('pincode.town.city', fn($q) => $q->whereIn('id', $cities));
// }

// if ($request->filled('town')) {
//     $towns = is_array($request->town) ? $request->town : [$request->town];
//     $query->whereHas('pincode.town', fn($q) => $q->whereIn('id', $towns));
// }


//     $data = $query->latest()->get();

//     if ($request->ajax()) return view('unclaimed-deposit-data', compact('data'))->render();

//     $states = State::all();
//     $cities = City::all();
//     $towns  = Pincode::with('town')->get()->pluck('town')->unique('id');

//     return view('unclaimed-deposit-list', compact('data','states','cities','towns'));
//     }

// working start
// public function unclaimedDeposit(Request $request)
// {
//     $query = UnclaimedDeposit::with('pincode.town.city.state');

//     if ($request->filled('search')) {
//         $search = $request->search;

//         $query->where(function ($q) use ($search) {
//             $q->where('description', 'like', "%{$search}%")
//               ->orWhere('name', 'like', "%{$search}%")
//               ->orWhereHas('pincode.town.city.state', fn ($q2) =>
//                     $q2->where('name', 'like', "%{$search}%")
//               )
//               ->orWhereHas('pincode.town.city', fn ($q2) =>
//                     $q2->where('name', 'like', "%{$search}%")
//               )
//               ->orWhereHas('pincode.town', fn ($q2) =>
//                     $q2->where('name', 'like', "%{$search}%")
//               );
//         });
//     }

//     if ($request->filled('town')) {
//         $towns = (array) $request->town;

//         $query->whereHas('pincode.town', fn ($q) =>
//             $q->whereIn('id', $towns)
//         );
//     }

//     if ($request->filled('city')) {
//         $cities = (array) $request->city;

//         $query->whereHas('pincode.town.city', fn ($q) =>
//             $q->whereIn('id', $cities)
//         );
//     }

//     if ($request->filled('state')) {
//         $states = (array) $request->state;

//         $query->whereHas('pincode.town.city.state', fn ($q) =>
//             $q->whereIn('id', $states)
//         );
//     }

//     $data = $query->latest()->get();

//     if ($request->ajax()) {
//         return view('unclaimed-deposit-data', compact('data'))->render();
//     }

//     return view('unclaimed-deposit-list', [
//         'data'   => $data,
//         'states' => State::all(),
//         'cities' => City::all(),
//         'towns'  => Town::all(),
//     ]);
// }

// working end


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
