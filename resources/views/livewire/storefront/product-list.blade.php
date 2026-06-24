<div class="bg-white">
    {{-- Product List --}}
    <div>

        <h1 class="flex items-center justify-center text-3xl font-bold tracking-tight text-tamanuleaf">Our Products </h1>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-wrap justify-center gap-x-6 gap-y-10">

                @foreach ($products as $product)

                    @php

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

                    <div class="group relative mx-2 max-w-sm min-w-xs p-4 border rounded-xl transition-all duration-900 ease-in-out hover:shadow-lg hover:border-gray-400">

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

        

                            </div>

                        </a>

                        {{-- test --}}
                        <div>
                            @foreach($product->productOptions as $option)

                                <div>
                                    <label>{{ $option->translate('name') }}</label>

                                    <select name="options[{{ $option->id }}]">
                                        @foreach($option->values as $value)
                                            <option value="{{ $value->id }}">
                                                {{ $value->translate('name') }}
                                            </option>
                                        @endforeach
                                    </select>

                                    price
                                    
                                </div>

                                


                            @endforeach
                        </div>
                        
                      

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