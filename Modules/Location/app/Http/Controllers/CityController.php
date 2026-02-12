<?php

namespace Modules\Location\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Location\Models\City;
use Modules\Location\Models\State;
use Modules\Location\Models\Pincode;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $data = QueryBuilder::for(City::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['name'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['name', 'created_at', 'state_id'])
            ->defaultSort('name')
            ->paginate(20)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('location::cities-data', [
                'data' => $data,
                'currentSort' => $currentSort,
                'currentOrder' => $currentOrder,
            ])->render();
        }
        return view('location::cities', [
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
        $states = State::orderBy('name', 'asc')->get();
        return view('location::create-city',compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate([
                'state_id'    => 'required',
                'new_state'   => 'nullable|required_if:state_id,add_new|string|max:150',
                'city'        => 'required|string|max:150',
                'city_slug'   => 'nullable|string|max:255',
            ]);

            DB::beginTransaction();

            try {
                if ($request->state_id === 'add_new') {
                    $state = State::firstOrCreate(['name' => $request->new_state]);
                    $stateId = $state->id;
                } else {
                    $stateId = (int) $request->state_id;
                }

                $slug = $request->filled('city_slug')
                    ? Str::slug($request->city_slug)
                    : Str::slug($request->city);

                $originalSlug = $slug;
                $counter = 1;

                while (City::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter++;
                }

                if (
                    City::where('name', $request->city)
                        ->where('state_id', $stateId)
                        ->exists()
                ) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'City already exists in this state'
                    ], 409);
                }

                $city = City::create([
                    'state_id'   => $stateId,
                    'name'       => $request->city,
                    'slug'       => $slug,
                ]);

                DB::commit();

                return response()->json([
                    'status'  => 'success',
                    'message' => 'City created successfully',
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $city = City::where('id', $id)
                    ->firstOrFail();
        $states = State::orderBy('name', 'asc')->get();
        return view('location::edit-city',compact('city','states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $request->validate([
            'state_id'    => 'required',
            'new_state'   => 'nullable|required_if:state_id,add_new|string|max:150',
            'city'        => 'required|string|max:150',
            'city_slug'   => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $city = City::find($id);

            if (!$city) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'City not found'
                ], 404);
            }

            if ($request->state_id === 'add_new') {
                $state = State::firstOrCreate(['name' => $request->new_state]);
                $stateId = $state->id;
            } else {
                $stateId = (int) $request->state_id;
            }


            $slug = $request->filled('city_slug')
                ? Str::slug($request->city_slug)
                : Str::slug($request->city);

            $originalSlug = $slug;
            $counter = 1;

            while (
                City::where('slug', $slug)
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $counter++;
            }

            if (
                City::where('name', $request->city)
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
        $item = City::findOrFail($id);
            if ($item->towns()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'It is linked with Town and cannot be deleted.'
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
            State::whereIn('id', $projectIds)->delete();
            return response()->json(['message' => 'Selected Data deleted permanently.']);
        }

        return response()->json(['message' => 'No Project selected for deletion.'], 400);
    }
}
