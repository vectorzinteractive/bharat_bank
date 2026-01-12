<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\AuctionState;
use App\Models\AuctionCity;
use App\Models\Pincode;
use Illuminate\Http\Request;
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
            return view('cms.auction-data', compact('projects', 'currentSort', 'currentOrder'))->render();
        }

        return view('cms.auctions', compact('projects', 'currentSort', 'currentOrder'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = AuctionState::orderBy('name', 'asc')->get();
        $cities = AuctionCity::orderBy('name', 'asc')->get();
        return view('cms.create-auction',compact('states','cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'     => 'required|string',
            'price'       => 'required|numeric|min:1',
            'square_feet' => 'nullable|numeric|min:1',
            'state_id'  => 'required',
            'new_state' => 'nullable|required_if:state_id,add_new|string|max:255',
            'city_id'  => 'required',
            'new_city' => 'nullable|required_if:city_id,add_new|string|max:255',

            'pincode_id'  => 'nullable|exists:pincode,id',
            'new_pincode' => 'nullable|required_if:city_id,add_new|digits:6',
        ]);


        DB::beginTransaction();

        try {
            if ($request->state_id === 'add_new') {
                $state = AuctionState::firstOrCreate(['name' => $request->new_state]);
                $stateId = $state->id;
            } else {
                $stateId = (int) $request->state_id;
            }

            if ($request->city_id === 'add_new') {
                $pincodeId = $request->filled('new_pincode')
                    ? Pincode::firstOrCreate(['pincode' => $request->new_pincode])->id
                    : (int) $request->pincode_id;

                $city = AuctionCity::firstOrCreate(
                    ['name' => $request->new_city, 'state_id' => $stateId],
                    ['pincode_id' => $pincodeId]
                );
                $cityId = $city->id;
            } else {
                $cityId = (int) $request->city_id;
            }


            // Create auction
            $auction = Auction::create([
                'description' => Purifier::clean($validated['content']),
                'state_id'    => $stateId,
                'city_id'     => $cityId,
                'price'       => $validated['price'],
                'sq_ft'       => $validated['square_feet'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Project Created Successfully',
                'redirectUrl' => 'cms-admin/auctions'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Auction $auction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $auction = Auction::where('id', $id)
                    ->firstOrFail();
        $states = AuctionState::orderBy('name', 'asc')->get();
        $cities = AuctionCity::orderBy('name', 'asc')->get();
        return view('cms.edit-auction',compact('states','cities', 'auction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'content'     => 'required|string',
        'price'       => 'required|numeric|min:1',
        'square_feet' => 'nullable|numeric|min:1',

        'state_id'  => 'required',
        'new_state' => 'nullable|required_if:state_id,add_new|string|max:255',

        'city_id'  => 'required',
        'new_city' => 'nullable|required_if:city_id,add_new|string|max:255',

        'pincode_id'  => 'nullable|exists:pincode,id',
        'new_pincode' => 'nullable|required_if:city_id,add_new|digits:6',
    ]);

    DB::beginTransaction();

    try {
        $auction = Auction::findOrFail($id);

        if ($request->state_id === 'add_new') {
            $state = AuctionState::firstOrCreate([
                'name' => trim(strip_tags($request->new_state))
            ]);
            $stateId = $state->id;
        } else {
            $stateId = (int) $request->state_id;
        }

        if ($request->city_id === 'add_new') {
            if ($request->filled('new_pincode')) {
                $pincode = Pincode::firstOrCreate([
                    'pincode' => $request->new_pincode
                ]);
                $pincodeId = $pincode->id;
            } elseif ($request->filled('pincode_id')) {
                $pincodeId = (int) $request->pincode_id;
            } else {
                throw new \Exception('Pincode is required.');
            }

            $city = AuctionCity::firstOrCreate(
                [
                    'name'     => trim(strip_tags($request->new_city)),
                    'state_id' => $stateId
                ],
                [
                    'pincode_id' => $pincodeId
                ]
            );
            $cityId = $city->id;
        } else {
            $cityId = (int) $request->city_id;
        }

        $auction->description = Purifier::clean($request->content);
        $auction->price       = $request->price;
        $auction->sq_ft       = $request->square_feet ?? null;
        $auction->state_id    = $stateId;
        $auction->city_id     = $cityId;

        $auction->save();

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'Auction Updated Successfully',
            'redirectUrl' => 'cms-admin/auctions'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Failed: ' . $e->getMessage()
        ], 500);
    }
}




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
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
