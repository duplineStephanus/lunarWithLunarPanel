<?php

namespace App\Livewire\Storefront;

use Livewire\Component;
use Livewire\WithPagination;
use Lunar\Models\Collection;
use Lunar\Models\Product;

class ProductList extends Component
{
    use WithPagination;

    public function render()
    {

        $collections = Collection::with([
            'media',
            'products.thumbnail',
            'products.prices.currency'
        ])->paginate(10);

        $products = Product::with([
            'variants.prices',
            'thumbnail',
        ])
        ->paginate(4);

        return view(
            'livewire.storefront.product-list', 
            [
                'collections' => $collections,
                'products' => $products 
            ]
        );
    }
}