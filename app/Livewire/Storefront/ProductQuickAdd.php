<?php 

namespace App\Livewire\Storefront;

use Livewire\Component;
use Lunar\Models\Product;
use Lunar\Models\ProductOptionValue;
use Lunar\Models\ProductVariant;
use Lunar\Models\Discount;

class ProductQuickAdd extends Component
{
    public Product $product;
    
    // Holds the selected ProductOptionValue ID (e.g., ID for "1 oz")
    public ?int $selectedOptionValueId = null;

    public function mount()
    {
        // Fail-safe default: load the very first variant's first assigned option value
        $firstVariant = $this->product->variants()->first(); //
        if ($firstVariant) {
            $this->selectedOptionValueId = $firstVariant->values()->first()?->id; //
        }
    }

    /**
     * Computed Property: Grab only option values available for this specific product's variants.
     */
    public function getAvailableSizesProperty()
    {
        return ProductOptionValue::query()
            ->whereHas('variants', function ($query) { //
                $query->where('product_id', $this->product->id); //
            })
            ->orderBy('position') //
            ->get();
    }

    /**
     * Computed Property: Dynamically locate the unique variant matching the customer's selection.
     */
    public function getCurrentVariantProperty(): ?ProductVariant
    {
        if (!$this->selectedOptionValueId) {
            return null;
        }

        return ProductVariant::query()
            ->where('product_id', $this->product->id) //
            ->whereHas('values', function ($query) { //
                $query->where('lunar_product_option_values.id', $this->selectedOptionValueId);
            })
            ->first();
    }

    /**
     * Computed Property: Pull calculations for prices and active discounts.
     */
    public function getPriceDataProperty(): array
    {
        $variant = $this->currentVariant;
        if (!$variant) {
            return ['original' => 0, 'current' => 0, 'discount' => null];
        }

        // Use standard HasPrices trait methods to fetch prices safely
        $basePriceModel = $variant->getPrices()->first(); //
        $originalPrice = $basePriceModel ? $basePriceModel->price->value / 100 : 0;

        // Query the discount model
        $activeDiscount = Discount::query()
            ->active()
            ->usable()
            ->where(function ($query) use ($variant) {
                $query->products([$this->product->id])
                      ->orWhere->productVariants([$variant->id]);
            })
            ->first();

        $currentPrice = $originalPrice;
        $discountLabel = null;

        if ($activeDiscount) {
            if ($activeDiscount->type === \Lunar\DiscountTypes\Percentage::class) {
                $percentage = $activeDiscount->data['percentage'] ?? 0;
                $currentPrice = $originalPrice * (1 - ($percentage / 100));
                $discountLabel = "{$percentage}% off";
            }
        }

        return [
            'original' => $originalPrice,
            'current' => $currentPrice,
            'discount' => $discountLabel,
        ];
    }

    public function render()
    {
        return view('livewire.storefront.product-quick-add');
    }
}