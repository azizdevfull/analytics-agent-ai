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
        return 'create_order tool chaqirishdan oldin
        search_products natijasidagi products ro‘yxatini diqqat bilan o‘qi.

        Agar user banan desa va search_products:
        [{id:6,name:"Banan"}]

        bo‘lsa, create_order da product_id = 6 yubor.

        Hech qachon product_id ni o‘zingdan taxmin qilma.';
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
                'found' => false,
                'message' => 'Hech qanday mahsulot topilmadi.',
            ]);
        }

        $data = json_encode([
            'found' => true,
            'query' => $query,
            'message' => 'Quyidagi mahsulotlar topildi:',
            'count' => $products->count(),
            'products' => $products->toArray(),
            'important' => 'Buyurtma yaratishda faqat yuqoridagi id lardan foydalaning. O\'zingizdan ID o\'ylab topmang.',
        ]);
        info($data);
        return $data;

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
