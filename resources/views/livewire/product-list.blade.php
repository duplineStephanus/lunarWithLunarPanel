<div>
    <div class="grid grid-cols-3 gap-6">
        @foreach ($products as $product)
            <div class="border rounded p-4">

                {{-- Thumbnail --}}
                @if ($product->thumbnail)
                    <img src="{{ $product->thumbnail->getUrl() }}" alt="{{ $product->translateAttribute('name') }}">
                @endif

                {{-- Name --}}
                <h2 class="text-lg font-semibold mt-2">
                    {{ $product->translateAttribute('name') }}
                </h2>

                {{-- Price from the first variant --}}
                @php
                    $variant = $product->variants->first();
                    $price = $variant?->prices->first();
                @endphp

                @if ($price)
                    <p class="text-gray-700">
                        {{ $price->price->formatted() }}
                    </p>
                @endif

                <a href="/products/{{ $product->defaultUrl?->slug }}" class="btn mt-3">
                    View Product
                </a>

            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>