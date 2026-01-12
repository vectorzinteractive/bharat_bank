<?php

namespace App\Http\Controllers;

use App\Models\AuctionState;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;

class AuctionStateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $data = QueryBuilder::for(AuctionState::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['name'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['name', 'created_at'])
            ->defaultSort('-created_at')
            ->paginate(20)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('cms.auction-states-data', [
                'data' => $data,
                'currentSort' => $currentSort,
                'currentOrder' => $currentOrder,
            ])->render();
        }
        return view('cms.auction-states', [
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'add_data' => 'required|string|max:255',
        ]);

        // $slug = Str::slug($request->add_data);

        // $originalSlug = $slug;
        // $counter = 1;

        // while (GenericProjectCategory::where('slug', $slug)->exists()) {
        //     $slug = $originalSlug . '-' . $counter++;
        // }

        if (AuctionState::where('name', $request->add_data)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Already exists!'
            ]);
        }

        $item = AuctionState::create([
            'name' => $request->add_data,
            // 'slug' => $slug
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Added successfully!',
            'data' => [
                'id' => $item->id,
                'name' => $item->name,
                'created_at' => $item->created_at->format('d F Y'),
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(AuctionState $auctionState)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data = AuctionState::find($id);
        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'add_data' => 'required|string|max:255',
        ]);

        //  $slug = Str::slug($request->add_data);

        // $originalSlug = $slug;
        // $counter = 1;

        // while (GenericProjectCategory::where('slug', $slug)->exists()) {
        //     $slug = $originalSlug . '-' . $counter++;
        // }

        $item = AuctionState::find($id);
        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found.'
            ], 404);
        }

        if (AuctionState::where('name', $request->add_data)
            ->where('id', '!=', $id)
            ->exists())
        {
            return response()->json([
                'success' => false,
                'message' => 'Already exists!'
            ], 409);
        }

        $item->update([
            'name' => $request->add_data,
            // 'slug' => $slug
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully!',
            'data' => [
                'id' => $item->id,
                'name' => $item->name,
                'created_at' => $item->created_at->format('d F Y'),
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $item = AuctionState::findOrFail($id);

            if ($item->city()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'It is linked and cannot be deleted.'
                ], 400);
            }

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully.'
        ]);
    }
}
