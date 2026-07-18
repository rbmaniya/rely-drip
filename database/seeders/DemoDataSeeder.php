<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteSetting::setMany([
            // 'site_name' => 'RelyDrip Jewellery',
            // 'company_name' => 'RelyDrip Jewellery Pvt. Ltd.',
            'contact_email' => 'support@relydrip.test',
            'contact_phone' => '+91 90000 00000',
            'tax_percentage' => '3',
            'shipping_flat_rate' => '99',
            'free_shipping_min_order' => '5000',
            'low_stock_threshold' => '5',
            'footer_copyright' => '© '.now()->year.' RelyDrip Jewellery. All rights reserved.',
        ]);

        $categories = collect([
            ['name' => 'Rings', 'short_description' => 'Engagement, wedding and casual rings.'],
            ['name' => 'Necklaces', 'short_description' => 'Statement and everyday necklaces.'],
            ['name' => 'Earrings', 'short_description' => 'Studs, hoops and drop earrings.'],
        ])->map(fn (array $data) => Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'short_description' => $data['short_description'],
            'status' => 'active',
        ]));

        $productSeeds = [
            [
                'category' => 'Rings',
                'title' => 'Classic Solitaire Diamond Ring',
                'description' => 'A timeless solitaire diamond ring crafted for everyday elegance.',
                'variations' => [
                    ['metal' => 'gold', 'color' => 'gold', 'gold_purity' => '18k', 'price' => 55000, 'stock' => 10],
                    ['metal' => 'gold', 'color' => 'rose_gold', 'gold_purity' => '22k', 'price' => 61000, 'stock' => 6],
                    ['metal' => 'platinum', 'color' => 'silver', 'gold_purity' => null, 'price' => 72000, 'stock' => 3],
                ],
            ],
            [
                'category' => 'Necklaces',
                'title' => 'Rose Gold Chain Necklace',
                'description' => 'Elegant rose gold chain necklace, perfect for layering.',
                'variations' => [
                    ['metal' => 'gold', 'color' => 'rose_gold', 'gold_purity' => '18k', 'price' => 48000, 'stock' => 8],
                    ['metal' => 'silver', 'color' => 'silver', 'gold_purity' => null, 'price' => 8500, 'stock' => 25],
                ],
            ],
            [
                'category' => 'Earrings',
                'title' => 'Silver Drop Earrings',
                'description' => 'Handcrafted silver drop earrings with a polished finish.',
                'variations' => [
                    ['metal' => 'silver', 'color' => 'silver', 'gold_purity' => null, 'price' => 4200, 'stock' => 2],
                ],
            ],
        ];

        $products = collect();

        foreach ($productSeeds as $seed) {
            $category = $categories->firstWhere('name', $seed['category']);

            $product = Product::create([
                'category_id' => $category->id,
                'title' => $seed['title'],
                'slug' => Str::slug($seed['title']),
                'description' => $seed['description'],
                'short_description' => $seed['description'],
                'status' => 'active',
                'is_featured' => true,
                'weight' => 12.5,
                'weight_unit' => 'gram',
            ]);

            $product->specifications()->create(['title' => 'Occasion', 'value' => 'Wedding', 'sort_order' => 0]);
            $product->specifications()->create(['title' => 'Finish', 'value' => 'Glossy', 'sort_order' => 1]);

            foreach ($seed['variations'] as $i => $variation) {
                $product->variations()->create([
                    'metal' => $variation['metal'],
                    'color' => $variation['color'],
                    'gold_purity' => $variation['gold_purity'],
                    'sku' => strtoupper(Str::slug($seed['title'], '')).'-'.($i + 1),
                    'price' => $variation['price'],
                    'stock' => $variation['stock'],
                    'min_stock_alert' => 5,
                    'status' => 'active',
                ]);
            }

            $products->push($product->fresh('variations'));
        }

        $customer = Customer::create([
            'name' => 'Priya Sharma',
            'email' => 'priya.sharma@example.com',
            'mobile' => '9876543210',
            'status' => 'active',
        ]);

        $customer->addresses()->create([
            'label' => 'shipping',
            'full_name' => 'Priya Sharma',
            'mobile' => '9876543210',
            'address_line' => '221B, Silver Oak Residency',
            'city' => 'Ahmedabad',
            'state' => 'Gujarat',
            'country' => 'India',
            'postal_code' => '380015',
            'is_default' => true,
        ]);

        $variation = $products->first()->variations->first();

        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'customer_id' => $customer->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'cod',
            'subtotal' => $variation->price,
            'shipping_charge' => 99,
            'tax_amount' => round($variation->price * 0.03, 2),
            'discount_amount' => 0,
            'grand_total' => $variation->price + 99 + round($variation->price * 0.03, 2),
            'shipping_full_name' => 'Priya Sharma',
            'shipping_mobile' => '9876543210',
            'shipping_address_line' => '221B, Silver Oak Residency',
            'shipping_city' => 'Ahmedabad',
            'shipping_state' => 'Gujarat',
            'shipping_country' => 'India',
            'shipping_postal_code' => '380015',
            'placed_at' => now(),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $products->first()->id,
            'product_variation_id' => $variation->id,
            'product_name' => $products->first()->title,
            'variation_label' => $variation->label,
            'sku' => $variation->sku,
            'quantity' => 1,
            'unit_price' => $variation->price,
            'total_price' => $variation->price,
        ]);
    }
}
