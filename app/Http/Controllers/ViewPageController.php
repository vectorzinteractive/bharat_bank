<?php

namespace App\Http\Controllers;

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
    // public function auctions()
    // {
    //      $auction = Auction::latest()->get();
    //      $states = State::all();
    //      $cities = City::all();
    //      $pincode = Pincode::all();
    //     return view('auctions-list',compact('auction','states','cities','pincode'));
    // }

    // public function auctions(Request $request)
    // {
    //     $query = Auction::query();

    //     // Join relationships for filtering
    //     $query->with(['pincode.town.city.state']);

    //     // Dynamic filters
    //     if ($request->filled('state')) {
    //         $query->whereHas('pincode.town.city.state', function($q) use ($request) {
    //             $q->whereIn('id', $request->state);
    //         });
    //     }

    //     if ($request->filled('city')) {
    //         $query->whereHas('pincode.town.city', function($q) use ($request) {
    //             $q->whereIn('id', $request->city);
    //         });
    //     }

    //     if ($request->filled('town')) {
    //         $query->whereHas('pincode.town', function($q) use ($request) {
    //             $q->whereIn('id', $request->town);
    //         });
    //     }

    //     if ($request->filled('price_max')) {
    //         $query->where('price', '<=', $request->price_max);
    //     }

    //     if ($request->filled('sqft_min')) {
    //         $query->where('sq_ft', '>=', $request->sqft_min);
    //     }

    //     if ($request->filled('sqft_max')) {
    //         $query->where('sq_ft', '<=', $request->sqft_max);
    //     }

    //     $auctions = $query->latest()->get();

    //     // If AJAX, return only the table HTML
    //     if ($request->ajax()) {
    //         return view('auction-data', compact('auctions'))->render();
    //     }

    //     // Otherwise, load full page
    //     $states = State::all();
    //     $cities = City::all();
    //     $towns  = Pincode::with('town')->get()->pluck('town')->unique('id');

    //     return view('auctions-list', compact('auctions', 'states', 'cities', 'towns'));
    // }
    public function auctions(Request $request)
    {
        $query = Auction::with('pincode.town.city.state');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('description', 'like', "%$search%")
              ->orWhereHas('pincode.town.city.state', fn($q2) => $q2->where('name', 'like', "%$search%"))
              ->orWhereHas('pincode.town.city', fn($q2) => $q2->where('name', 'like', "%$search%"))
              ->orWhereHas('pincode.town', fn($q2) => $q2->where('name', 'like', "%$search%"));
        });
    }

    if ($request->filled('state')) {
    $states = is_array($request->state) ? $request->state : [$request->state];
    $query->whereHas('pincode.town.city.state', fn($q) => $q->whereIn('id', $states));
}

if ($request->filled('city')) {
    $cities = is_array($request->city) ? $request->city : [$request->city];
    $query->whereHas('pincode.town.city', fn($q) => $q->whereIn('id', $cities));
}

if ($request->filled('town')) {
    $towns = is_array($request->town) ? $request->town : [$request->town];
    $query->whereHas('pincode.town', fn($q) => $q->whereIn('id', $towns));
}

    if ($request->filled('price_min')) $query->where('price', '>=', $request->price_min);
    if ($request->filled('price_max')) $query->where('price', '<=', $request->price_max);
    if ($request->filled('sqft_min')) $query->where('sq_ft', '>=', $request->sqft_min);
    if ($request->filled('sqft_max')) $query->where('sq_ft', '<=', $request->sqft_max);

    $auctions = $query->latest()->get();

    if ($request->ajax()) return view('auction-data', compact('auctions'))->render();

    $states = State::all();
    $cities = City::all();
    $towns  = Pincode::with('town')->get()->pluck('town')->unique('id');

    return view('auctions-list', compact('auctions','states','cities','towns'));
    }

//     public function filterAuctions(Request $request)
// {
//     $query = Auction::query();

//     $states = $request->input('states', []);
//     $cities = $request->input('cities', []);
//     $price = $request->input('price');
//     $sqftMin = $request->input('sqft_min');
//     $sqftMax = $request->input('sqft_max');

//     if (!empty($states)) {
//         $query->whereIn('state_id', array_map('intval', $states));
//     }

//     if (!empty($cities)) {
//         $query->whereIn('city_id', array_map('intval', $cities));
//     }

//     if ($price !== null && $price !== '') {
//         $query->where('price', '<=', $price);
//     }

//     if ($sqftMin !== null && $sqftMin !== '') {
//         $query->where('sq_ft', '>=', $sqftMin);
//     }

//     if ($sqftMax !== null && $sqftMax !== '') {
//         $query->where('sq_ft', '<=', $sqftMax);
//     }

//     $auctions = $query->with(['state', 'city'])->get()->map(function ($auction) {
//         return [
//             'id' => $auction->id,
//             'description' => $auction->description,
//             'price' => $auction->price,
//             'sq_ft' => $auction->sq_ft,
//             'state_name' => $auction->state->name ?? null,
//             'city_name' => $auction->city->name ?? null,
//         ];
//     });

//     return response()->json($auctions);
// }





}
