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
            'variants.prices',
            'thumbnail',
        ])
        ->paginate(4);

        return view('livewire.storefront.product-list', ['products' => $products]);
    }
}