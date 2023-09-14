<?php

namespace App\Modules\Ingredient\Listeners;

use App\Modules\Ingredient\Events\LowStockLevelEvent;
use App\Modules\Ingredient\Notifications\LowStockLevelNotification;
use Illuminate\Support\Facades\Notification;

class SendLowStockLevelNotification 
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockLevelEvent $event): void
    {
        $ingredient = $event->ingredient->fresh();

        if( $ingredient->notified_at )
            return ;
        
        Notification::route('mail', config('merchant.email'))
                    ->notify(new LowStockLevelNotification($ingredient));

        $ingredient->update([
            'notified_at' => now(),
        ]);
    }
}
