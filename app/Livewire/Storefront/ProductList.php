<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Models\Product;

class ProductList extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::with([
            'thumbnail',
            'variants.prices.currency', // Eagerloads prices instantly
            'variants.values.option'    // Eagerloads option value labels (Sizes)
        ])
        ->whereStatus('published')
        ->paginate(12);

        return view(
            'livewire.storefront.product-list', 
            [
                'products' => $products 
            ]
        );
    }
}