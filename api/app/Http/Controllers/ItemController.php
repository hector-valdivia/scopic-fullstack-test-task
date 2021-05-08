<?php

namespace App\Http\Controllers;

use App\Actions\AutoBidItem;
use App\Actions\CreateBid;
use App\Actions\ValidateBid;
use App\Models\Item;
use App\Models\ItemAutoBid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Item::with('owner:id,name,email', 'lastBidUser:id,name,email')
            ->when($request->input('name'), function($q, $name){
                $q->where('name', 'like', '%'.$name.'%');
            })
            ->when($request->input('user_id'), function ($q, $user_id){
                $q->where('user_id', $user_id);
            })
            ->when($request->input('last_bided_user_id'), function($q, $last_bided_user_id){
                $q->where('last_bided_user_id', $last_bided_user_id);
            });

        $data = $request->input('all')? $query->get() : $query->paginate($request->input('limit', 10));

        return response()->json($data->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $item = Item::create($request->toArray());

        return response()->json([
            'message' => 'Success created',
            'data'    => $item->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Item $item)
    {
        $item->load([
            'owner:id,name,email',
            'lastBidUser:id,name,email'
        ]);

        return response()->json($item->toArray());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Item $item)
    {
        $item->fill($request->toArray());
        $item->save();

        return response()->json([
            'message' => 'Successfully updated',
        ]);
    }

    /**
     * @param Request $request
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function bid(Request $request, Item $item){
        $request->validate([
            'bid' => ['required', 'numeric']
        ]);

        $user = $request->user();

        #Rules
        if ($item->user_id === $user->id){
            throw ValidationException::withMessages([
                'user_id' => ['You can bid to your own item']
            ]);
        }

        if ($item->last_bid_user_id === $user->id){
            throw ValidationException::withMessages([
                'last_bid_user_id' => ['You are already the highest bidder']
            ]);
        }

        if ($item->last_bid >= $request->bid){
            throw ValidationException::withMessages([
                'bid' => ['The bid is to short']
            ]);
        }

        Bus::chain([
            CreateBid::makeJob($item, $request->user(), $request->bid),
            AutoBidItem::makeJob($item, $request->user()),
        ])->dispatch();

        return response()->json([
            'message' => 'Bid registered',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Item  $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Successfully deleted',
        ]);
    }
}
