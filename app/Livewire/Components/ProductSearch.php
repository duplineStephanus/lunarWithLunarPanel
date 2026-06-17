<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Lunar\Models\Product;
use Lunar\Search\Facades\Search;

class ProductSearch extends Component
{
    public bool $isOpen = false;
    public string $search = '';

    protected $listeners = ['openSearch' => 'toggleModal'];

    public function toggleModal(): void
    {
        $this->isOpen = !$this->isOpen;
        
        if (!$this->isOpen) {
            $this->search = '';
        }
    }

    public function render()
    {
        $results = null;
        $products = collect();

        if (mb_strlen($this->search) >= 2) {
            // 1. Fetch raw documents from Meilisearch
            $results = Search::query($this->search)
                ->perPage(5)
                ->get(); // Returns a Lunar\Search\Data\SearchResults object

            // 2. Pluck unique IDs from the search documents
            $productIds = collect($results->hits)->pluck('document.id');

            // 3. Eager-load actual Eloquent models from the database for secure display
            $products = Product::whereIn('id', $productIds)
                ->with([
                    'variants.prices.currency',
                    'media',
                    'brand',
                    'defaultUrl',
                ])
                ->get()
                ->keyBy('id');
        }

        return view('livewire.components.product-search', [
            'results' => $results,
            'products' => $products,
        ]);
    }
}