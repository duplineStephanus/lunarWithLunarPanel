<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use Lunar\Models\Product;
use Lunar\Models\ProductVariant;
use Lunar\Facades\CartSession;

class ProductQuickAdd extends Component
{
    public Product $product;
    public ?int $selectedVariantId = null;

    public function mount(Product $product)
    {
        $this->product = $product;

        // Automatically pre-select the first available variant
        $firstVariant = $this->product->variants->first();
        if ($firstVariant) {
            $this->selectedVariantId = $firstVariant->id;
        }
    }

    /**
     * Computed property to safely grab the currently selected variant
     */
    public function getSelectedVariantProperty(): ?ProductVariant
    {
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    /**
     * Computed property to fetch live price data (including tier, currency, and discounts)
     */
    public function getPricingProperty()
    {
        $variant = $this->selectedVariant;

        if (!$variant) {
            return null;
        }

        // Lunar's pricing manager automatically checks for active group/cart discounts
        return $variant->pricing()->get();
    }

    /**
     * Add the selected size variant to the active Lunar cart session
     */
    public function addToCart()
    {
        if (!$this->selectedVariantId) {
            return;
        }

        $cart = CartSession::current();

        if (!$cart) {
            $cart = CartSession::create();
        }

        $cart->add($this->selectedVariant, 1);

        // Optional: Emit a browser event or global Livewire event to update a nav cart counter
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.storefront.product-quick-add');
    }
}