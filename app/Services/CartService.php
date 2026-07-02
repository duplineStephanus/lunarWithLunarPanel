<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

/**
 * Simple CartService that stores a guest cart in session and attempts to call
 * Lunar add-to-cart action when the Lunar package is available.
 *
 * Data layout in session: 'cart' => [ 'lines' => [ variant_id => qty, ... ] ]
 */
class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * Add a purchasable (variant) to the cart and return the total item count.
     *
     * @param int|string $variantId
     * @param int $quantity
     * @return int New total quantity in cart
     */
    public static function add($variantId, int $quantity = 1): int
    {
        // Update local session cart (guest)
        $cart = Session::get(self::SESSION_KEY, ['lines' => []]);

        $currentQty = (int) ($cart['lines'][$variantId] ?? 0);
        $cart['lines'][$variantId] = $currentQty + $quantity;

        Session::put(self::SESSION_KEY, $cart);

        $total = self::count();

        // Attempt to sync to Lunar if available (best-effort)
        if (class_exists(\Lunar\Actions\Carts\AddOrUpdatePurchasable::class)) {
            try {
                self::syncLineToLunar($variantId, $quantity);
            } catch (\Throwable $e) {
                // swallow errors so session cart remains usable; you can log if desired
                // logger()->error('Lunar cart sync failed: '.$e->getMessage());
            }
        }

        return $total;
    }

    /**
     * Return total item count in the session cart.
     *
     * @return int
     */
    public static function count(): int
    {
        $cart = Session::get(self::SESSION_KEY, ['lines' => []]);
        return array_sum($cart['lines'] ?? []);
    }

    /**
     * Optional: clear session cart (useful for testing).
     *
     * @return void
     */
    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Best-effort helper: try to call Lunar add-to-cart action to sync the guest action.
     *
     * NOTE: This method attempts to find or create a Lunar cart for the current request or
     * authenticated user. Because vendor implementations may vary, this uses cautious checks.
     *
     * @param int|string $variantId
     * @param int $quantity
     */
    protected static function syncLineToLunar($variantId, int $quantity = 1): void
    {
        if (!class_exists(\Lunar\Actions\Carts\AddOrUpdatePurchasable::class)) {
            return;
        }

        $actionClass = config('lunar.cart.actions.add_to_cart', \Lunar\Actions\Carts\AddOrUpdatePurchasable::class);
        $action = app($actionClass);

        $cart = null;

        // 1) If authenticated, attempt to get user cart
        if (auth()->check()) {
            try {
                $user = auth()->user();
                if (method_exists($user, 'cart')) {
                    $cart = $user->cart()->first();
                }
            } catch (\Throwable $e) {
                $cart = null;
            }

            if (!$cart && class_exists(\Lunar\Models\Cart::class)) {
                $cart = \Lunar\Models\Cart::where('user_id', auth()->id())->first();
            }
        }

        // 2) Guest: try to find/create a cart by fingerprint/session
        if (!$cart && class_exists(\Lunar\Models\Cart::class)) {
            try {
                // Use a simple fingerprint heuristic — replace with your project's generator if needed
                $fingerprint = request()->ip() . '-' . (session()->getId() ?? '');
                $cart = \Lunar\Models\Cart::firstOrCreate(
                    ['fingerprint' => $fingerprint],
                    ['currency' => config('lunar.currency', 'USD')]
                );
            } catch (\Throwable $e) {
                $cart = null;
            }
        }

        if (!$cart) {
            return;
        }

        // Find the purchasable (variant)
        $purchasable = null;
        if (class_exists(\Lunar\Models\ProductVariant::class)) {
            $purchasable = \Lunar\Models\ProductVariant::find($variantId);
        }

        if (!$purchasable) {
            return;
        }

        // Call the configured action (execute or callable)
        if (is_callable([$action, 'execute'])) {
            $action->execute($cart, $purchasable, ['quantity' => $quantity]);
        } elseif (is_callable($action)) {
            $action($cart, $purchasable, ['quantity' => $quantity]);
        }
    }
}