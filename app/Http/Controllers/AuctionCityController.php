<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\AuctionState;
use App\Models\AuctionCity;
use App\Models\Pincode;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;

class AuctionCityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $data = QueryBuilder::for(AuctionCity::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['name'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['name', 'created_at', 'state_id'])
            ->defaultSort('name')
            ->paginate(20)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('cms.auctions.auction-cities-data', [
                'data' => $data,
                'currentSort' => $currentSort,
                'currentOrder' => $currentOrder,
            ])->render();
        }
        return view('cms.auctions.auction-cities', [
            'data' => $data,
            'currentSort' => $currentSort,
            'currentOrder' => $currentOrder,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = AuctionState::orderBy('name', 'asc')->get();
        $pincode = Pincode::orderBy('pincode', 'asc')->get();
        return view('cms.auctions.create-city',compact('states','pincode'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        $request->validate([
            'state_id'    => 'required',
            'new_state'   => 'nullable|required_if:state_id,add_new|string|max:150',
            'city'        => 'required|string|max:150',
            'city_slug'   => 'nullable|string|max:255',
            'pincode_id'  => 'required',
            'new_pincode' => 'nullable|required_if:pincode_id,add_new|digits:6',
        ]);

        DB::beginTransaction();

        try {
            if ($request->state_id === 'add_new') {
                $state = AuctionState::firstOrCreate(['name' => $request->new_state]);
                $stateId = $state->id;
            } else {
                $stateId = (int) $request->state_id;
            }

            if ($request->pincode_id === 'add_new') {
                $pincode = Pincode::firstOrCreate(['pincode' => $request->new_pincode]);
                $pincodeId = $pincode->id;
            } else {
                $pincodeId = (int) $request->pincode_id;
            }

            $slug = $request->filled('city_slug')
                ? Str::slug($request->city_slug)
                : Str::slug($request->city);

            $originalSlug = $slug;
            $counter = 1;

            while (AuctionCity::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            if (
                AuctionCity::where('name', $request->city)
                    ->where('state_id', $stateId)
                    ->exists()
            ) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'City already exists in this state'
                ], 409);
            }

            $city = AuctionCity::create([
                'state_id'   => $stateId,
                'name'       => $request->city,
                'slug'       => $slug,
                'pincode_id' => $pincodeId,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'City created successfully',
                'data'    => [
                    'id'         => $city->id,
                    'name'       => $city->name,
                    'slug'       => $city->slug,
                    'state_id'   => $city->state_id,
                    'pincode_id' => $city->pincode_id,
                    'created_at' => $city->created_at->format('d F Y'),
                ],
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Duplicate entry detected'
            ], 409);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AuctionCity $auctionCity)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
    {
        $city = AuctionCity::where('id', $id)
                    ->firstOrFail();
        $states = AuctionState::orderBy('name', 'asc')->get();
        $pincode = Pincode::orderBy('pincode', 'asc')->get();
        return view('cms.auctions.edit-city',compact('city','states', 'pincode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'state_id'    => 'required',
        'new_state'   => 'nullable|required_if:state_id,add_new|string|max:150',
        'city'        => 'required|string|max:150',
        'city_slug'   => 'nullable|string|max:255',
        'pincode_id'  => 'required',
        'new_pincode' => 'nullable|required_if:pincode_id,add_new|digits:6',
    ]);

    DB::beginTransaction();

    try {
        $city = AuctionCity::find($id);

        if (!$city) {
            return response()->json([
                'status'  => 'error',
                'message' => 'City not found'
            ], 404);
        }

        if ($request->state_id === 'add_new') {
            $state = AuctionState::firstOrCreate(['name' => $request->new_state]);
            $stateId = $state->id;
        } else {
            $stateId = (int) $request->state_id;
        }

        if ($request->pincode_id === 'add_new') {
            $pincode = Pincode::firstOrCreate(['pincode' => $request->new_pincode]);
            $pincodeId = $pincode->id;
        } else {
            $pincodeId = (int) $request->pincode_id;
        }

        $slug = $request->filled('city_slug')
            ? Str::slug($request->city_slug)
            : Str::slug($request->city);

        $originalSlug = $slug;
        $counter = 1;

        while (
            AuctionCity::where('slug', $slug)
                ->where('id', '!=', $id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter++;
        }

        if (
            AuctionCity::where('name', $request->city)
                ->where('state_id', $stateId)
                ->where('id', '!=', $id)
                ->exists()
        ) {
            return response()->json([
                'status'  => 'error',
                'message' => 'City already exists in this state'
            ], 409);
        }

        $city->update([
            'state_id'   => $stateId,
            'name'       => $request->city,
            'slug'       => $slug,
            'pincode_id' => $pincodeId,
        ]);

        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => 'City updated successfully',
        ]);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Duplicate entry detected'
        ], 409);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status'  => 'error',
            'message' => 'Something went wrong'
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $item = AuctionCity::findOrFail($id);
            if ($item->auctions()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'It is linked with Auction and cannot be deleted.'
                ], 400);
            }

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully.'
        ]);
    }

    public function bulkDelete(Request $request) {
        $projectIds = $request->selected_ids;

        if (!empty($projectIds)) {
            AuctionState::whereIn('id', $projectIds)->delete();
            return response()->json(['message' => 'Selected Data deleted permanently.']);
        }

        return response()->json(['message' => 'No Project selected for deletion.'], 400);
    }
}
