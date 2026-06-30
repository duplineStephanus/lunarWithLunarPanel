<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use Lunar\Facades\CartSession;

class CartTotal extends Component
{
    public int $cartTotal = 0;

    #[\Livewire\Attributes\On('cart-updated')]
    public function updateCartTotal($total = null)
    {
        if ($total !== null) {
            $this->cartTotal = $total;
        } else {
            $this->refreshCartTotal();
        }
    }

    public function mount()
    {
        $this->refreshCartTotal();
    }

    private function refreshCartTotal(): void
    {
        try {
            $cart = CartSession::current();
            $this->cartTotal = $cart ? $cart->lines->sum('quantity') : 0;
        } catch (\Exception $e) {
            $this->cartTotal = 0;
        }
    }

    public function render()
    {
        return view('livewire.cart.cart-total');
    }
}