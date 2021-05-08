<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = User::query()
            ->when($request->input('name'), function($q, $name){
                $q->where('name', 'like', '%'.$name.'%');
            })
            ->when($request->input('email'), function($q, $name){
                $q->where('name', 'like', '%'.$name.'%');
            });

        $data = $request->input('all')? $query->get() : $query->paginate($request->input('limit', 10));

        return response()->json($data->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param null $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id = null)
    {
        $user = $id? User::findOrFail($id) : $request->user();
        return response()->json($user->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMe(UserUpdateRequest $request){
        return $this->update($request, $request->user());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->fill($request->validated());
        $user->save();

        return response()->json([
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Successfully deleted'
        ]);
    }
}
