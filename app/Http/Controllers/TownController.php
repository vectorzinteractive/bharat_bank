<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\State;
use App\Models\Town;
use App\Models\Pincode;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;

class TownController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $data = QueryBuilder::for(Town::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['name'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['name', 'created_at', 'city_id'])
            ->defaultSort('name')
            ->paginate(20)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('cms.location.town-data', [
                'data' => $data,
                'currentSort' => $currentSort,
                'currentOrder' => $currentOrder,
            ])->render();
        }
        return view('cms.location.town', [
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
        $cities = City::orderBy('name', 'asc')->get();
        return view('cms.location.create-town',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
    {
        $request->validate([
            'city_id'    => 'required',
            'new_city'   => 'nullable|required_if:city_id,add_new|string|max:150',
            'town'       => 'required|string|max:150',
            'slug'       => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            if ($request->city_id === 'add_new') {
                $city = City::firstOrCreate(['name' => $request->new_city]);
                $cityId = $city->id;
            } else {
                $cityId = (int) $request->city_id;
            }

            $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->town);
            $originalSlug = $slug;
            $counter = 1;

            while (Town::whereRaw('LOWER(slug) = ?', [strtolower($slug)])->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            if (Town::where('name', $request->town)->where('city_id', $cityId)->exists()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Town already exists'
                ], 409);
            }

            $town = Town::create([
                'city_id' => $cityId,
                'name'    => $request->town,
                'slug'    => $slug,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Town created successfully',
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
        $town = Town::where('id', $id)
                    ->firstOrFail();
        $cities = City::orderBy('name', 'asc')->get();
        return view('cms.location.edit-town',compact('town','cities'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
    {
        $request->validate([
            'city_id'    => 'required',
            'new_city'   => 'nullable|required_if:city_id,add_new|string|max:150',
            'town'       => 'required|string|max:150',
            'slug'       => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $town = Town::find($id);

            if (!$town) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Town not found'
                ], 404);
            }

            if ($request->city_id === 'add_new') {
                $city = City::firstOrCreate(['name' => $request->new_city]);
                $cityId = $city->id;
            } else {
                $cityId = (int) $request->city_id;
            }

            $slug = $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->town);
            $originalSlug = $slug;
            $counter = 1;

            while (Town::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
                ->where('id', '!=', $id)
                ->exists()
            ) {
                $slug = $originalSlug . '-' . $counter++;
            }

            if (Town::where('name', $request->town)
                ->where('city_id', $cityId)
                ->where('id', '!=', $id)
                ->exists()
            ) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Town already exists in this state'
                ], 409);
            }

            $town->update([
                'city_id' => $cityId,
                'name'    => $request->town,
                'slug'    => $slug,
            ]);

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Town updated successfully',
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
        $item = Town::findOrFail($id);
            if ($item->pincodes()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'It is linked with Pincode and cannot be deleted.'
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
            Town::whereIn('id', $projectIds)->delete();
            return response()->json(['message' => 'Selected Data deleted permanently.']);
        }

        return response()->json(['message' => 'No Project selected for deletion.'], 400);
    }
}
