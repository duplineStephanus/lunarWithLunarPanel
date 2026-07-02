<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Services\CartService;

class CartCount extends Component
{
    public int $count = 0;

    protected $listeners = [
        'cartUpdated' => 'setCount',
    ];

    public function mount()
    {
        $this->count = CartService::count();
    }

    public function setCount($count)
    {
        $this->count = (int) $count;
    }

    public function render()
    {
        return view('livewire.cart.cart-count');
    }
}