<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use Lunar\Models\Discount;
use Lunar\Models\Discountable;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class ProductCard extends Component
{
    public Product $product;
    
    // Tracks selected option values, e.g., [option_id => value_id]
    public array $selectedOptions = [];

    /**
     * Component Lifecycle Mount
     */
    public function mount(Product $product)
    {
        $this->product = $product;
        $this->initializeDefaultOptions();
    }

    /**
     * Pre-populate the options state with the first available values.
     */
    public function initializeDefaultOptions(): void
    {
        foreach ($this->product->productOptions as $option) {
            $firstValue = $option->values->first();
            if ($firstValue) {
                $this->selectedOptions[$option->id] = $firstValue->id;
            }
        }
    }

    /**
     * Computed Property: Resolves the matching variant based on selected options.
     */
    public function getVariantProperty(): ?ProductVariant
    {
        if (empty($this->selectedOptions)) {
            return $this->product->variants->first();
        }

        // Filter variants where the values match the selected criteria completely
        return $this->product->variants->first(function ($variant) {
            $variantValueIds = $variant->values->pluck('id')->toArray();
            
            // Check if every selected option value is present in this variant
            return !array_diff($this->selectedOptions, $variantValueIds);
        }) ?? $this->product->variants->first();
    }

    /**
     * Computed Property: Retrieves the pricing for the currently active variant.
     */
    public function getPriceProperty()
    {
        $variant = $this->variant;
        
        // Lunar's standard pricing manager scales via the active user/currency session
        return $variant ? $variant->pricing()->get() : null;
    }

    /**
     * NEW: Get applicable discounts for the product or current variant
     * Uses polymorphic relationship through lunar_discountables table
     */
    public function getDiscountProperty(): ?Discount
    {
        $variant = $this->variant;
        $now = now();
        
        $discount = Discount::query()
            // Get discounts that either:
            // 1. Have no discountables (applies to all products), OR
            // 2. Have a discountable entry matching this product or variant
            ->where(function ($query) {
                $query->whereDoesntHave('discountables')  // No limitations = applies to all
                    ->orWhereHas('discountables', function ($q) {
                        $q->where(function ($subQ) {
                            // Match product discounts
                            $subQ->where('discountable_type', 'product')
                                ->where('discountable_id', $this->product->id)
                                ->where('type', 'limitation');
                        })->orWhere(function ($subQ) {
                            // Or match variant-specific discounts
                            $subQ->where('discountable_type', 'product_variant')
                                ->where('discountable_id', $this->variant?->id)
                                ->where('type', 'limitation');
                        });
                    });
            })
            // Ensure the discount is active (within date range)
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            // Order by priority (higher priority first)
            ->orderBy('priority', 'desc')
            // Get the first matching discount
            ->first();
        
        return $discount;
    }

    /**
     * Calculate the discounted price based on current discount
     */
    public function getDiscountedPriceProperty()
    {
        $price = $this->price?->matched?->price;
        $discount = $this->discount;
        
        if (!$price || !$discount) {
            return $price; // Return original price if no discount
        }
        
        $discountData = $discount->data;
        $priceAmount = $price->value / 100; // Price is already a numeric value
        //dd($priceAmount);
        
        // If it's a percentage discount
        if (isset($discountData['percentage']) && $discountData['percentage']) {
            $percentage = $discountData['percentage'];
            $discountAmount = ($priceAmount * $percentage) / 100;
            return $priceAmount - $discountAmount;
        }
        
        // If it's a fixed value discount
        if (isset($discountData['fixed_value']) && $discountData['fixed_value']) {
            $fixedValues = $discountData['fixed_values'] ?? [];
            // Get the USD value (or first available currency)
            $discountAmount = $fixedValues['USD'] ?? array_values($fixedValues)[0] ?? 0;
            // Convert from cents to dollars
            $discountAmount = $discountAmount / 100;
            return max(0, $priceAmount - $discountAmount); // Don't go below 0
        }
        
        return $price; // Return original if no applicable discount
    }

    public function render()
    {
        return view('livewire.storefront.product-card', [
            'currentPrice' => $this->price?->matched?->price,
            'discountedPrice' => $this->discountedPrice,
            'discount' => $this->discount,
        ]);
    }

}