<?php

namespace App\Actions;

use App\Models\Item;
use App\Models\ItemAutoBid;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class AutoBidItem
{
    use AsAction;

    public function getJobTags(Item $item): array
    {
        return ['autobid', 'item:'.$item->id];
    }

    public function getJobDisplayName(): string
    {
        return 'Bot auto bid';
    }

    public function handle(Item $item) : Bool
    {
        $autobids = ItemAutoBid::with('user:id,max_amount_bid')
            ->where('item_id', $item->id)
            ->orderBy('created_at')
            ->get();

        $increment = $autobids->count();
        $the_bid   = $item->last_bid + $increment;

        foreach($autobids as $bid){
            $user = $bid->user;
            if (is_null($user->max_amount_bid) || $user->budget() > $the_bid){
                BidItem::dispatch($item, $user, $the_bid);
                break;
            }
        }

        return true;
    }
}
