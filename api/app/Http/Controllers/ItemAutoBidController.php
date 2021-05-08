<?php

namespace App\Http\Controllers;

use App\Actions\ValidateBid;
use App\Models\Item;
use App\Models\ItemAutoBid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ItemAutoBidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $data = ItemAutoBid::query()
            ->with('item:id,name,ends_at,created_at')
            ->whereHas('item', function($q){
                $q->where('ends_at', '>', Carbon::now());
            })
            ->where('user_id', $user->id)
            ->get();

        return response()->json($data->toArray());
    }

    /**
     * Bid for the item.
     *
     * @param \Illuminate\Http\Request $request
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function subscribe(Request $request, Item $item){
        $current_user = $request->user();

        if ($item->user_id === $current_user->id){
            throw ValidationException::withMessages([
                'user_id' => ['You can bid to your own item']
            ]);
        }

        $data = [
            'item_id' => $item->id,
            'user_id' => $current_user->id,
        ];

        ItemAutoBid::upsert($data, $data);

        return response()->json([
            'message' => 'Subscribed auto-bid'
        ]);
    }

    public function unsubscribe(Request $request, Item $item){
        $current_user = $request->user();

        ItemAutoBid::where([
            'item_id' => $item->id,
            'user_id' => $current_user->id,
        ])->delete();

        return response()->json([
            'message' => 'Removed auto-bid'
        ]);
    }
}
