<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentSort = $request->query('sort', '-created_at');
        $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
        $currentSort = ltrim($currentSort, '-');

        $data = QueryBuilder::for(State::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new SearchFilter(['name'])),
                AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
            ])
            ->allowedSorts(['name', 'created_at'])
            ->defaultSort('name')
            ->paginate(30)
            ->appends($request->query());

        if ($request->ajax()) {
            return view('cms.location.states-data', [
                'data' => $data,
                'currentSort' => $currentSort,
                'currentOrder' => $currentOrder,
            ])->render();
        }
        return view('cms.location.states', [
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
        return view('cms.location.create-state');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'state'      => 'required|string|max:150',
            'state_slug' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            if (State::where('name', $request->state)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'State already exists'
                ], 422);
            }

            $slug = $request->state_slug
                ? Str::slug($request->state_slug)
                : Str::slug($request->state);

            $originalSlug = $slug;
            $counter = 1;

            while (State::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $state = State::create([
                'name' => $request->state,
                'slug' => $slug,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'State created successfully',
                'data' => [
                    'id'         => $state->id,
                    'name'       => $state->name,
                    'slug'       => $state->slug,
                    'created_at' => $state->created_at->format('d F Y'),
                ],
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(State $auctionState)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $auctionState = State::where('id', $id)
                    ->firstOrFail();
        return view('cms.location.edit-state',compact('auctionState'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'state'      => 'required|string|max:150',
            'state_slug' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $state = State::find($id);

            if (!$state) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'State not found'
                ], 404);
            }

            if (
                State::where('name', $request->state)
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'State already exists'
                ], 409);
            }

            $slug = Str::slug($request->state_slug ?? $request->state);
            $originalSlug = $slug;
            $counter = 1;

            while (
                State::where('slug', $slug)
                    ->where('id', '!=', $id)
                    ->exists()
            ) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $state->update([
                'name' => $request->state,
                'slug' => $slug,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'State updated successfully',
                'data' => [
                    'id'         => $state->id,
                    'name'       => $state->name,
                    'slug'       => $state->slug,
                    'created_at' => $state->created_at->format('d F Y'),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $item = State::findOrFail($id);
            if ($item->city()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'It is linked to city and cannot be deleted.'
                ], 400);
            }

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully.'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->selected_ids;

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No State selected for deletion.'
            ], 400);
        }

        $deleted = [];
        $blocked = [];

        $states = State::whereIn('id', $ids)->get();

        foreach ($states as $state) {
            if ($state->city()->exists()) {
                $blocked[] = [
                    'id' => $state->id,
                    'reason' => 'Linked with city'
                ];
                continue;
            }

            $state->delete();
            $deleted[] = $state->id;
        }

        return response()->json([
            'status' => 'success',
            'deleted_ids' => $deleted,
            'blocked' => $blocked,
            'message' => 'Bulk delete completed with partial success.'
        ]);
    }

}
