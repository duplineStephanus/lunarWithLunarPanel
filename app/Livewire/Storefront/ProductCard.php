<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
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

    public function render()
    {
        return view('livewire.storefront.product-card', [
            'currentPrice' => $this->price?->matched?->price
        ]);
    }
}