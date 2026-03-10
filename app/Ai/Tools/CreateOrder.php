<?php

namespace App\Ai\Tools;

use App\Models\Order;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CreateOrder implements Tool
{
    public function description(): Stringable|string
    {
        return 'Foydalanuvchi tasdiqlagan buyurtmani yaratadi. Faqat foydalanuvchi tasdiqlagan mahsulot product_id va name larini yuboring.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $user_id = $request['user_id'];
        $product_id = $request['product_id'];
        $quantity = $request['quantity'];

        $order = Order::create([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'quantity' => $quantity
        ]);

        return json_encode([
            'success' => true,
            'order_id' => $order->id
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'user_id' => $schema->integer()
                ->description('Foydalanuvchi id si')
                ->required(),
            'product_id' => $schema->integer()
                ->description('search_products dan qaytgan product_id')
                ->required(),
            'quantity' => $schema->integer()
                ->description('Miqdor')
                ->min(1)
                ->required(),
        ];
    }
}
