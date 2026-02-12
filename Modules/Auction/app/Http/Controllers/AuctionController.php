<?php

namespace Modules\Auction\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Auction\Models\Auction;
use Modules\Location\Models\State;
use Modules\Location\Models\City;
use Modules\Location\Models\Town;
use Modules\Location\Models\Pincode;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $projects = QueryBuilder::for(Auction::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['description'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['description', 'created_at'])
            ->defaultSort('-created_at')
            ->paginate(20)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('auction::auction-data', compact('projects', 'currentSort', 'currentOrder'))->render();
        }

        return view('auction::index', compact('projects', 'currentSort', 'currentOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::orderBy('name', 'asc')->get();
        $cities = City::orderBy('name', 'asc')->get();
        $towns = Town::orderBy('name', 'asc')->get();
        $pincodes = Pincode::orderBy('pincode', 'asc')->get();
        return view('auction::create',compact('states','cities' , 'towns' , 'pincodes'));
        // return view('auction::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
            $validated = $request->validate([
            'content'     => 'required|string',
            'price'       => 'required|numeric|min:1',
            'square_feet' => 'nullable|numeric|min:1',

            'state_id' => 'required',
            'city_id'  => 'required',
            'town_id'  => 'required',

            'pincode_id' => 'required_unless:town_id,add_new',

            'new_city'    => 'nullable|required_if:city_id,add_new|string|max:255',
            'new_town'    => 'nullable|required_if:town_id,add_new|string|max:255',
            'new_pincode' => 'nullable|required_if:town_id,add_new|digits:6',
        ]);

        DB::beginTransaction();

        try {

            $stateId = $request->state_id === 'add_new'
                ? State::firstOrCreate(['name' => $request->new_state])->id
                : (int) $request->state_id;

            $cityId = $request->city_id === 'add_new'
                ? City::firstOrCreate([
                    'name' => $request->new_city,
                    'state_id' => $stateId
                ])->id
                : (int) $request->city_id;

            $townId = $request->town_id === 'add_new'
                ? Town::firstOrCreate([
                    'name' => $request->new_town,
                    'city_id' => $cityId
                ])->id
                : (int) $request->town_id;

            if ($request->town_id === 'add_new') {
                $pincodeId = Pincode::firstOrCreate([
                    'pincode' => $request->new_pincode,
                    'town_id' => $townId
                ])->id;
            } else {
                $pincodeId = (int) $request->pincode_id;
            }

            Auction::create([
                'description' => Purifier::clean($validated['content']),
                'pincode_id'  => $pincodeId,
                'price'       => $validated['price'],
                'sq_ft'       => $validated['square_feet'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Auction created successfully',
                'redirectUrl' => 'cms-admin/auctions'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $auction = Auction::with([
            'pincode.town.city.state'
        ])->findOrFail($id);

        $states = State::all();

        $cities = City::where(
            'state_id',
            $auction->pincode->town->city->state->id
        )->get();

        $towns = Town::where(
            'city_id',
            $auction->pincode->town->city->id
        )->get();

        $pincodes = Pincode::where(
            'town_id',
            $auction->pincode->town->id
        )->get();

        return view('auction::edit', compact(
            'auction', 'states', 'cities', 'towns', 'pincodes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $validated = $request->validate([
            'content'     => 'required|string',
            'price'       => 'required|numeric|min:1',
            'square_feet' => 'nullable|numeric|min:1',

            'state_id' => 'required',
            'city_id'  => 'required',
            'town_id'  => 'required',

            'pincode_id' => 'required_unless:town_id,add_new',

            'new_state'   => 'nullable|required_if:state_id,add_new|string|max:255',
            'new_city'    => 'nullable|required_if:city_id,add_new|string|max:255',
            'new_town'    => 'nullable|required_if:town_id,add_new|string|max:255',
            'new_pincode' => 'nullable|required_if:town_id,add_new|digits:6',
        ]);

        DB::beginTransaction();

        try {

            $auction = Auction::findOrFail($id);

            /* ---------------- STATE ---------------- */
            $stateId = $request->state_id === 'add_new'
                ? State::firstOrCreate([
                    'name' => trim(strip_tags($request->new_state))
                ])->id
                : (int) $request->state_id;

            /* ---------------- CITY ---------------- */
            $cityId = $request->city_id === 'add_new'
                ? City::firstOrCreate([
                    'name'     => trim(strip_tags($request->new_city)),
                    'state_id'=> $stateId
                ])->id
                : (int) $request->city_id;

            /* ---------------- TOWN ---------------- */
            $townId = $request->town_id === 'add_new'
                ? Town::firstOrCreate([
                    'name'    => trim(strip_tags($request->new_town)),
                    'city_id'=> $cityId
                ])->id
                : (int) $request->town_id;

            /* ---------------- PINCODE ---------------- */
            if ($request->town_id === 'add_new') {
                $pincodeId = Pincode::firstOrCreate([
                    'pincode' => $request->new_pincode,
                    'town_id'=> $townId
                ])->id;
            } else {
                $pincodeId = (int) $request->pincode_id;
            }

            /* ---------------- UPDATE AUCTION ---------------- */
            $auction->update([
                'description' => Purifier::clean($validated['content']),
                'pincode_id'  => $pincodeId,
                'price'       => $validated['price'],
                'sq_ft'       => $validated['square_feet'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Auction updated successfully',
                'redirectUrl' => 'cms-admin/auctions'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        DB::beginTransaction();

        try {
            $project = Auction::findOrFail($id);

            $project->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Project and associated files deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request) {
        $projectIds = $request->selected_ids;

        if (!empty($projectIds)) {
            Auction::whereIn('id', $projectIds)->delete();
            return response()->json(['message' => 'Selected Data deleted permanently.']);
        }

        return response()->json(['message' => 'No Project selected for deletion.'], 400);
    }
}
