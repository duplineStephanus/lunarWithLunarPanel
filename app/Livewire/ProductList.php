<?php

namespace App\Livewire;

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
        ->paginate(12);

        return view('livewire.product-list', ['products' => $products]);
    }
}