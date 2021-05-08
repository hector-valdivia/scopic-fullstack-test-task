<?php

namespace App\Actions;

use App\Models\Item;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBid
{
    use AsAction;

    public function getJobTags(Item $item): array
    {
        return ['bid', 'item:'.$item->id];
    }

    public function getJobDisplayName(): string
    {
        return 'User bid';
    }

    public function handle(Item $item, User $user, $the_bid = 1) : Bool
    {
        $item->last_bid = $the_bid;
        $item->last_bid_user_id = $user->id;
        $item->save();

        return true;
    }

    public function asJob(Item $item, User $user, $the_bid = 1): void
    {
        $this->handle($item, $user, $the_bid);
    }
}
