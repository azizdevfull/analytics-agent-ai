<?php

namespace App\Ai\Tools;

use App\Enums\OrderStatus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class OrderStatsTool implements Tool
{

    public function description(): Stringable|string
    {
        return 'from va to yuborasan va status xam yuborsang boladi status bu senga kerakli status va senga order soni qaytadi:
        order statuslari';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $from = $request['from'];
        $to = $request['to'];
        $from = Carbon::parse($from)->startOfDay();
        $to = Carbon::parse($to)->endOfDay();

        $status = $request['status'];
        $count = Order::whereBetween('created_at', [$from, $to])->when($status, fn($query) => $query->where('status', $status))->count();
        return $count;
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'from' => $schema->string()->required(),
            'to' => $schema->string()->required(),
            'status' => $schema->string()->enum(OrderStatus::values()),
        ];
    }
}
