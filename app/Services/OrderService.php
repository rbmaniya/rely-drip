<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Models\Admin;
use App\Models\Order;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function updateStatus(Order $order, OrderStatus $newStatus, ?string $note, Admin $admin): Order
    {
        return DB::transaction(function () use ($order, $newStatus, $note, $admin) {
            $currentStatus = $order->status;

            if ($currentStatus === $newStatus) {
                return $order;
            }

            if (! in_array($newStatus, $currentStatus->nextStatuses(), true)) {
                throw ValidationException::withMessages([
                    'status' => "Cannot move an order from \"{$currentStatus->label()}\" to \"{$newStatus->label()}\".",
                ]);
            }

            if ($currentStatus === OrderStatus::Pending && $newStatus === OrderStatus::Confirmed) {
                $this->adjustStock($order, decrement: true);
            }

            if ($newStatus === OrderStatus::Cancelled && $currentStatus !== OrderStatus::Pending) {
                $this->adjustStock($order, decrement: false);
            }

            $order->update(['status' => $newStatus]);

            $order->statusHistories()->create([
                'status' => $newStatus,
                'note' => $note,
                'changed_by' => $admin->id,
            ]);

            return $order->fresh(['statusHistories']);
        });
    }

    private function adjustStock(Order $order, bool $decrement): void
    {
        foreach ($order->items as $item) {
            if (! $item->product_variation_id) {
                continue;
            }

            if ($decrement) {
                ProductVariation::whereKey($item->product_variation_id)->decrement('stock', $item->quantity);
            } else {
                ProductVariation::whereKey($item->product_variation_id)->increment('stock', $item->quantity);
            }
        }
    }
}
