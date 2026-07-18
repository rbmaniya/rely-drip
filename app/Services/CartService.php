<?php

namespace App\Services;

use App\Models\ProductVariation;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function add(ProductVariation $variation, int $quantity = 1): void
    {
        $cart = $this->raw();
        $desired = ($cart[$variation->id] ?? 0) + max($quantity, 1);
        $cart[$variation->id] = min($desired, max($variation->stock, 0));

        if ($cart[$variation->id] <= 0) {
            unset($cart[$variation->id]);
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function updateQuantity(string $variationId, int $quantity): void
    {
        $cart = $this->raw();

        if (! isset($cart[$variationId])) {
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$variationId]);
        } else {
            $cart[$variationId] = $quantity;
        }

        session([self::SESSION_KEY => $cart]);
    }

    public function remove(string $variationId): void
    {
        $cart = $this->raw();
        unset($cart[$variationId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * @return Collection<int, object{variation: ProductVariation, requested_quantity: int, quantity: int, insufficient_stock: bool, line_total: float}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        return ProductVariation::query()
            ->with(['product.images'])
            ->whereIn('id', array_keys($cart))
            ->get()
            ->map(function (ProductVariation $variation) use ($cart) {
                $requested = $cart[$variation->id];
                $available = max($variation->stock, 0);
                $quantity = min($requested, $available);

                return (object) [
                    'variation' => $variation,
                    'requested_quantity' => $requested,
                    'quantity' => $quantity,
                    'insufficient_stock' => $requested > $available,
                    'line_total' => (float) $variation->price * $quantity,
                ];
            })
            ->values();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }

    public function hasItems(): bool
    {
        return $this->count() > 0;
    }

    /**
     * @return array<string, int>
     */
    private function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }
}
