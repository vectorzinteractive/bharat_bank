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
use Illuminate\Validation\Rule;

class PincodeController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $data = QueryBuilder::for(Pincode::class)
            ->with(['town'])
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['pincode'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['town_id','pincode', 'created_at'])
            ->paginate(20)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('cms.location.pincode-data', [
                'data' => $data,
                'currentSort' => $currentSort,
                'currentOrder' => $currentOrder,
            ])->render();
        }

        return view('cms.location.pincodes', [
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
        $towns = Town::orderBy('name', 'asc')->get();
        return view('cms.location.create-pincode',compact('towns'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    $request->validate([
        'town_id' => 'required|exists:towns,id',
        'pincode' => [
            'required',
            'digits:6',
            Rule::unique('pincode')->where(function ($query) use ($request) {
                return $query->where('town_id', $request->town_id);
            }),
        ],
    ]);

    DB::beginTransaction();

    try {
        $pincode = Pincode::create([
            'town_id' => (int) $request->town_id,
            'pincode' => $request->pincode,
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Pincode created successfully',
            'data' => $pincode
        ], 201);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => 'Duplicate pincode detected'
        ], 409);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong'
        ], 500);
    }
}


    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
    {
        $pincode = Pincode::where('id', $id)
                    ->firstOrFail();
        $towns = Town::orderBy('name', 'asc')->get();
        return view('cms.location.edit-pincode',compact('pincode','towns'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $request->validate([
        'town_id' => 'required|exists:towns,id',
        'pincode' => [
            'required',
            'digits:6',
            Rule::unique('pincode')
                ->where(fn ($query) => $query->where('town_id', $request->town_id))
                ->ignore($id),
        ],
    ]);

    DB::beginTransaction();

    try {
        $pincode = Pincode::find($id);

        if (!$pincode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pincode not found'
            ], 404);
        }

        $pincode->update([
            'town_id' => (int) $request->town_id,
            'pincode' => $request->pincode,
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Pincode updated successfully'
        ]);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => 'Duplicate pincode detected'
        ], 409);

    } catch (\Exception $e) {
        DB::rollBack();

        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong'
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $item = Pincode::findOrFail($id);
            if ($item->auctions()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'It is linked cannot be deleted.'
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
            Pincode::whereIn('id', $projectIds)->delete();
            return response()->json(['message' => 'Selected Data deleted permanently.']);
        }

        return response()->json(['message' => 'No Project selected for deletion.'], 400);
    }
}
