<?php

namespace App\Ai\Tools;

use App\Models\Product;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchProducts implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Mahsulotlarni nom bo\'yicha qidiradi. Foydalanuvchi mahsulot so\'raganda ALBATTA shu tool\'ni chaqir.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = $request['query'];

        $products = Product::where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'price')->limit(5)->get();

        if (!$products) {
            return json_encode([
                'success' => false,
                'message' => 'Hech qanday mahsulot topilmadi.',
            ]);
        }

        return json_encode([
            'success' => true,
            'message' => 'Quyidagi mahsulotlar topildi:',
            'products' => $products,
        ]);

    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Mahsulot nomi bo\'yicha izlash uchun so\'rov')
                ->required(),
        ];
    }
}
