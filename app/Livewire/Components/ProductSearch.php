<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Lunar\Models\Product;

class ProductSearch extends Component
{
    // Properties to track state
    public bool $isOpen = false;
    public string $search = '';

    // Event listener array to allow other components (like your navbar) to trigger this search modal
    protected $listeners = ['openSearch' => 'toggleModal'];

    public function toggleModal(): void
    {
        $this->isOpen = !$this->isOpen;
        
        // Clear previous search query string when closing the modal layout
        if (!$this->isOpen) {
            $this->search = '';
        }
    }

    public function render()
    {
        $results = collect();

        // Only run database queries once the user has typed at least 2 characters
        if (strlen($this->search) >= 2) {
            $results = Product::with(['thumbnail', 'variants.prices'])
                ->whereHas('variants', function ($query) {
                    $query->where('sku', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('attributeData', function ($query) {
                    // Custom JSON attribute search syntax matching Lunar's EAV architecture
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->take(5) // Limit the real-time preview list to top 5 hits to preserve high performance
                ->get();
        }

        return view('livewire.components.product-search', [
            'results' => $results
        ]);
    }
}