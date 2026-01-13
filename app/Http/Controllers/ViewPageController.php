<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Auction;
use App\Models\AuctionState;
use App\Models\AuctionCity;
use App\Models\Pincode;

class ViewPageController extends Controller
{
    public function auctions()
    {
         $auction = Auction::all();
         $states = AuctionState::all();
         $cities = AuctionCity::all();
         $pincode = Pincode::all();
        return view('auctions',compact('auction','states','cities','pincode'));
    }

    public function filterAuctions(Request $request)
{
    $query = Auction::query();

    $states = $request->input('states', []);
    $cities = $request->input('cities', []);
    $price = $request->input('price');
    $sqftMin = $request->input('sqft_min');
    $sqftMax = $request->input('sqft_max');

    if (!empty($states)) {
        $query->whereIn('state_id', array_map('intval', $states));
    }

    if (!empty($cities)) {
        $query->whereIn('city_id', array_map('intval', $cities));
    }

    if ($price !== null && $price !== '') {
        $query->where('price', '<=', $price);
    }

    if ($sqftMin !== null && $sqftMin !== '') {
        $query->where('sq_ft', '>=', $sqftMin);
    }

    if ($sqftMax !== null && $sqftMax !== '') {
        $query->where('sq_ft', '<=', $sqftMax);
    }

    $auctions = $query->with(['state', 'city'])->get()->map(function ($auction) {
        return [
            'id' => $auction->id,
            'description' => $auction->description,
            'price' => $auction->price,
            'sq_ft' => $auction->sq_ft,
            'state_name' => $auction->state->name ?? null,
            'city_name' => $auction->city->name ?? null,
        ];
    });

    return response()->json($auctions);
}





}
