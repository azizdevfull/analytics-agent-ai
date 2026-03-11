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
        return 'bu toolda sen maxsulotlarni izlab topasan
        found true bolsa maxsulot bor boladi found false chiqsa maxsulot topilmadi degan xabar ber
        ';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $name = $request['name'];

        $products = Product::where('name', 'like', "%{$name}%")->get();
        if ($products->isEmpty()) {
            return json_encode([
                'found' => false,
                'products' => [],
                'message' => 'Maxsulot topilmadi',
            ]);
        }

        return json_encode([
            'found' => true,
            'products' => $products,
        ]);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('Maxsulotni name boyicha izlash')
                ->required(),
        ];
    }
}
