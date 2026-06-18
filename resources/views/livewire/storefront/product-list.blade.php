<div>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">

                @foreach ($products as $product)

                    @php
                        $variant = $product->variants->first();
                        $price = $variant?->prices->first();

                        $description = html_entity_decode(
                            strip_tags(
                                $product->translateAttribute('description') ?? ''
                            )
                        );

                        $shortDescription = \Illuminate\Support\Str::limit(
                            trim($description),
                            400,
                            '...'
                        );
                    @endphp

                    <div class="group relative p-4 rounded-xl transition duration-200 ease-in-out ring-offset-2 hover:ring-2 hover:ring-goldennut">

                        <a href="{{ route('storefront.products.show', $product->defaultUrl?->slug)  }}">
                            {{-- Product Image --}}
                            @if ($product->thumbnail)
                                <img
                                    src="{{ $product->thumbnail->getUrl() }}"
                                    alt="{{ $product->translateAttribute('name') }}"
                                    class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:bg-opacity-75 lg:h-80"
                                >
                            @else
                                <div class="aspect-square w-full rounded-md bg-gray-100 lg:h-80"></div>
                            @endif

                            <div class="mt-4 flex justify-between gap-4">

                                <div class="flex-1">

                                    {{-- Product Name --}}
                                    <h3 class="text-sm font-medium text-gray-900">
                                        {{ $product->translateAttribute('name') }}
                                    </h3>

                                    {{-- Product Description --}}
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $shortDescription }}
                                    </p>

                                </div>

                                {{-- Price --}}
                                @if ($price)
                                    <p class="text-sm font-medium text-gray-900 whitespace-nowrap">
                                        {{ $price->price->formatted() }}
                                    </p>
                                @endif

                            </div>

                        </a>

                    </div>

                    

                @endforeach

            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $products->links() }}
            </div>

        </div>
    </div>
</div>