<div class="bg-white">

    {{-- Featured Products --}}
    <div class="space-y-12">

        @foreach($collections as $collection)

            @if($collection->products->isNotEmpty())
                 {{-- show all featured products --}}
                <div>
                    
                    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">

                        <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                            {{ $collection->translateAttribute('name') }}
                        </h2>
                        
                        <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">

                            @foreach($collection->products as $product)

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

                                <div class="group relative p-4 rounded-xl transition duration-200 ease-in-out ring-offset-2 hover:bg-tamanuleaf/10">
                                    <a href="{{ route('storefront.products.show', $product->defaultUrl?->slug)  }}">

                                        @if($product->thumbnail)
                                            <img
                                                src="{{ $product->thumbnail->getUrl() }}"
                                                alt="{{ $product->translateAttribute('name') }}"
                                                class="aspect-square w-full rounded-md bg-gray-200 lg:aspect-auto lg:h-80"
                                            />
                                        @endif

                                        <div class="mt-4 flex justify-between">

                                        <div>

                                            <h3 class="text-lg font-medium text-gray-700">
                                                <a href="#">
                                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                                    {{ $product->translateAttribute('name') }}
                                                </a>
                                            </h3>

                                            <p class="mt-1 text-sm text-gray-500">{{$shortDescription}}</p>

                                        </div>

                                        @if($price)
                                            <p class="text-sm font-medium text-gray-900">{{ $price->price->formatted()  }}</p>
                                        @endif

                                        </div>
                                    </a>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            @endif
         
        @endforeach

    </div> 

    {{-- if user is logged in, show buy again (previous purchases) & previously viewed items --}}

    {{-- Product List --}}
    <div>

        <h1 class="flex items-center justify-center text-3xl font-bold tracking-tight text-tamanuleaf"> All Our Products </h1>

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