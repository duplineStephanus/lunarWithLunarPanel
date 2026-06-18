<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use Lunar\Facades\CartSession;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;

class ProductShow extends Component
{
    public Product $product;
    public ?ProductVariant $selectedVariant = null;
    public array $variantSelections = [];
    public int $quantity = 1;

    /**
     * Initialize the component using Lunar's URL Slug system.
     */
    public function mount(string $slug)
    {
        $this->product = Product::with([
            'variants.prices.currency',
            'variants.images',
            'media',
        ])->whereHas('urls', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->firstOrFail();

        // Establish initial variant selection options mapping
        $this->initializeVariants();
    }

    /**
     * Map out product option values and auto-select the default or first variant.
     */
    public function initializeVariants()
    {
        $defaultVariant = $this->product->defaultVariant;
        
        if ($defaultVariant) {
            $this->selectedVariant = $defaultVariant;
            // E.g., ['size' => 'S', 'color' => 'blue']
            $this->variantSelections = $defaultVariant->options->pluck('value.name', 'handle')->toArray();
        }
    }

    /**
     * Real-time hook executing when a user updates an option radio button.
     */
    public function updatedVariantSelections()
    {
        // Find the specific variant that matches the user's selected combinations
        $this->selectedVariant = $this->product->variants->first(function ($variant) {
            $variantOptions = $variant->options->pluck('value.name', 'handle')->toArray();
            return empty(array_diff_assoc($this->variantSelections, $variantOptions));
        });
    }

    /**
     * Dispatch reactive action to Lunar's Cart Engine.
     */
    public function addToBag()
    {
        $this->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (!$this->selectedVariant) {
            session()->flash('error', 'Please select all available options.');
            return;
        }

        // Add to Lunar session cart
        CartSession::add($this->selectedVariant, $this->quantity);

        // Emit an event to refresh companion components like the 'MiniCart'
        $this->dispatch('cart-updated');

        session()->flash('message', 'Added to bag successfully!');
    }

    public function render()
    {
        return view('livewire.storefront.product-show')
            ->layout('layouts.app');
    }
}