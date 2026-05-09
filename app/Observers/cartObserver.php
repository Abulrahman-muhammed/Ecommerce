<?php

namespace App\Observers;

use App\Models\cart;
use Illuminate\Support\Str;
class cartObserver
{
    /**
     * Handle the cart "created" event.
     */
    public function creating(cart $cart): void
    {
        $cart->id = (string) Str::uuid();
    }

}
