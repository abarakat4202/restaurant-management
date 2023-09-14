<?php

namespace Tests\Feature;

use App\Modules\Ingredient\Models\Ingredient;
use App\Modules\Ingredient\Notifications\LowStockLevelNotification;
use App\Modules\Product\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_order_and_updating_stock()
    {
        Notification::fake();

        $product = Product::factory()->create();

        $ingredient1 = Ingredient::factory()->create(['name' => 'Beef', 'full_stock' => 20000]);
        $ingredient2 = Ingredient::factory()->create(['name' => 'Cheese', 'full_stock' => 5000]);
        $ingredient3 = Ingredient::factory()->create(['name' => 'Onion', 'full_stock' => 1000]);

        $product->ingredients()->attach([
            $ingredient1->id => ['amount' => 150, 'unit' => $ingredient1->unit],
            $ingredient2->id => ['amount' => 30, 'unit' => $ingredient2->unit],
            $ingredient3->id => ['amount' => 20, 'unit' => $ingredient3->unit],
        ]);

        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('ingredients', 3);
        $this->assertDatabaseCount('product_ingredient', 3);

        $data = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ];

        $response = $this->postJson('/api/orders', $data);

        $response->assertStatus(Response::HTTP_CREATED);

        // Assert that the order was created in the database
        $this->assertDatabaseCount('orders', 1);

        // Assert that the ingredient stock levels were updated correctly
        $this->assertEquals($ingredient1->stock_level - (2 * 150), $ingredient1->fresh()->stock_level);
        $this->assertEquals($ingredient2->stock_level - (2 * 30), $ingredient2->fresh()->stock_level);
        $this->assertEquals($ingredient3->stock_level - (2 * 20), $ingredient3->fresh()->stock_level);
    }

    /**
     * @test
     */
    public function it_sends_low_stock_notification_to_merchant_first_time_only()
    {
        Notification::fake();

        $product = Product::factory()->create();
        $ingredient = Ingredient::factory()->create([
            'full_stock' => 25, // Below the threshold (50% of initial stock)
            'notify_stock_percentage' => 0.5, // Set the threshold percentage
        ]);

        $product->ingredients()->attach([
            $ingredient->id => ['amount' => 15, 'unit' => $ingredient->unit],
        ]);

        $data = [
            'products' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                ],
            ],
        ];

        $order1 = $this->post('/api/orders', $data);
        
        // Assert that the notification was sent
        Notification::assertSentOnDemand(
            LowStockLevelNotification::class,
            function (LowStockLevelNotification $notification, $channels, AnonymousNotifiable $notifiable) use ($ingredient) {
                return $notification->ingredient->id === $ingredient->id && $notifiable->routes['mail'] == config('merchant.email');
            }
        );

        // Assert that the notified_at timestamp was updated
        $this->assertNotNull($ingredient->fresh()->notified_at);

        $ingredient->update(['full_stock' => 25]);
        $order2 = $this->post('/api/orders', $data);
        Notification::assertSentOnDemandTimes(LowStockLevelNotification::class, 1);
    }

    
}
