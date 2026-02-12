<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use App\Filters\SearchFilter;
use App\Filters\DateRangeFilter;
class UserController extends Controller
{


    public function index(Request $request)
{
    $currentSort  = $request->query('sort', '-created_at');
    $currentOrder = str_starts_with($currentSort, '-') ? 'desc' : 'asc';
    $currentSort  = ltrim($currentSort, '-');

    $users = QueryBuilder::for(User::class)
        ->whereDoesntHave('roles', function ($q) {
            $q->where('name', 'super-admin');
        })
        ->allowedFilters([
            AllowedFilter::custom('search', new SearchFilter(['name', 'email'])),
            AllowedFilter::custom('date_range', new DateRangeFilter('created_at')),
        ])
        ->allowedSorts(['name', 'email', 'created_at'])
        ->defaultSort('-created_at')
        ->paginate(25)
        ->appends($request->query());

    if ($request->ajax()) {
        return view('cms.admin.users-data', compact(
            'users',
            'currentSort',
            'currentOrder'
        ))->render();
    }

    return view('cms.admin.users-list', compact(
        'users',
        'currentSort',
        'currentOrder'
    ));
}


    public function create()
    {
        $authUser = auth()->user();

        $roles = Role::whereIn('name',
            $authUser->hasRole('super-admin')
                ? ['admin', 'editor']
                : ['editor']
        )->get();

         $permissions = Permission::where('name', 'like', '%.access')
        ->orderBy('name')
        ->get();

        return view('cms.admin.create', compact('roles', 'permissions'));
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $authUser = auth()->user();

    //         $validated = $request->validate([
    //             'name'          => 'required|string|max:255',
    //             'email'         => 'required|email|unique:users,email',
    //             'password'      => 'required|min:6',
    //             'role'          => 'required|exists:roles,name',
    //             'permissions'   => 'nullable|array',
    //             'permissions.*' => 'exists:permissions,name',
    //         ]);

    //         if (
    //             $authUser->hasRole('admin') &&
    //             $validated['role'] !== 'editor'
    //         ) {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Admins can only create editors'
    //             ], 403);
    //         }

    //         DB::beginTransaction();

    //         $user = User::create([
    //             'name'     => $validated['name'],
    //             'email'    => $validated['email'],
    //             'password' => Hash::make($validated['password']),
    //         ]);

    //         $user->assignRole($validated['role']);

    //         if (!empty($validated['permissions'])) {
    //             $user->syncPermissions($validated['permissions']);
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'status'  => true,
    //             'message' => 'User created successfully',
    //             'redirectUrl' => 'cms-admin/users'

    //         ], 201);

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $e->errors()
    //         ], 422);

    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'status'  => false,
    //             'message' => 'Something went wrong',
    //             'error'   => app()->isLocal() ? $e->getMessage() : null
    //         ], 500);
    //     }
    // }


    public function store(Request $request)
    {
        try {
            $authUser = auth()->user();

            $validated = $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email',
                'password'      => 'required|min:6',
                'role'          => 'required|exists:roles,name',
                'permissions'   => 'nullable|array',
                'permissions.*' => 'exists:permissions,name',
            ]);

            if (
                $authUser->hasRole('admin') &&
                $validated['role'] !== 'editor'
            ) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Admins can only create editors'
                ], 403);
            }

            DB::beginTransaction();

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // $user->assignRole($validated['role']);
            $user->syncRoles([$validated['role']]);


            if ($validated['role'] === 'admin') {

                $permissions = Permission::where('name', 'like', '%.access')->pluck('name');
                $user->syncPermissions($permissions);

            } elseif (!empty($validated['permissions'])) {

                $user->syncPermissions($validated['permissions']);
            }

            DB::commit();

            return response()->json([
                'status' => "success",
                'message' => 'User created successfully',
                'redirectUrl' => url('cms-admin/users')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => "error",
                'errors' => $e->errors()
            ], 422);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => "error",
                'message' => 'Something went wrong',
                'error' => app()->isLocal() ? $e->getMessage() : null
            ], 500);
        }
    }
    public function edit(string $id)
{
    $user = User::findOrFail($id);
    $authUser = auth()->user();

    if ($authUser->hasRole('admin') && $user->hasRole('super-admin')) {
        abort(403);
    }

    $isSelfSuperAdminEdit = $authUser->hasRole('super-admin') && $authUser->id === $user->id;

    $roles = $isSelfSuperAdminEdit
        ? Role::where('name', 'super-admin')->get()
        : Role::whereIn('name', ['admin', 'editor'])->orderBy('name')->get();

    $permissions = Permission::where('name', 'like', '%.access')->orderBy('name')->get();

    return view('cms.admin.edit', compact('user', 'roles', 'permissions', 'isSelfSuperAdminEdit'));
}


    // public function update(Request $request, User $user)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'role' => 'required',
    //         'permissions' => 'array'
    //     ]);

    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => $request->password
    //             ? bcrypt($request->password)
    //             : $user->password,
    //     ]);

    //     // Sync role
    //     $user->syncRoles([$request->role]);

    //     // Sync permissions
    //     $user->syncPermissions($request->permissions ?? []);

    //     return response()->json([
    //         'status' => "success",
    //         'message' => 'User updated successfully'
    //     ]);
    // }


    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'permissions' => 'nullable|array'
        ]);

        if (
            $authUser->id === $user->id &&
            $user->hasRole('super-admin') &&
            $request->role !== 'super-admin'
        ) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot change your own Super Admin role.'
            ], 403);
        }

        if (
            $authUser->hasRole('admin') &&
            $user->hasRole('super-admin')
        ) {
            abort(403);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
                ? bcrypt($request->password)
                : $user->password,
        ]);

        $user->syncRoles([$request->role]);

        if ($request->role === 'admin') {

            $permissions = Permission::where('name', 'like', '%.access')->pluck('name');
            $user->syncPermissions($permissions);

        } else {

            $user->syncPermissions($request->permissions ?? []);
        }

        if ($authUser->id === $user->id && $request->role !== 'admin') {
            return response()->json([
                'status' => "success",
                'message' => 'Role updated. Redirecting...',
                'redirectUrl' => url('cms-admin/dashboard')
            ]);
        }

        return response()->json([
            'status' => "success",
            'message' => 'User updated successfully'
        ]);
    }

    public function destroy(User $user)
    {
        if (
            auth()->user()->hasRole('admin') &&
            $user->hasRole('super-admin')
        ) {
            return response()->json([
                'status' => false,
                'message' => 'You do not have permission to delete this user.'
            ], 403);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot delete yourself'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }



}
