<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:admin')->only('store');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
//        TODO: Apply filter

        return UserResource::collection(User::query()->when(auth()->user()->role->isAdmin(), function ($query) {
            $query->with('roles');
        })->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request, UserService $userService)
    {
        try {
            $userService->createUser(
                $request->name,
                $request->username,
                $request->email,
                $request->password,
                $request->roles,
            );
        } catch (\Exception) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not create a new user, please try again later.');
        }

        return \response()->json([
            'message' => 'User ' . $request->email . ' created successfully.'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user, UserService $userService)
    {
        try {
            $userService->updateUser($user, $request->name, $request->username, $request->email);
        } catch (\Exception) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not update user ' . $user->email . ', please try again later.');
        }

        return \response()->json([
            'message' => 'User ' . $request->email . ' updated successfully.'
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        try {
            $user->delete();
        } catch (\Exception) {
            return abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not delete user ' . $user->email . ', please try again later.');
        }

        return \response()->json(
            ['message' => 'User ' . $user->email . ' deleted successfully.'],
            Response::HTTP_OK,
        );
    }
}
