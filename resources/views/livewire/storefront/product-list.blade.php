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


                         {{--  Nest a livewire here. The Livewire will first get the  size of the product that user selects, (if any discont associated get the discount wheter it is percentage or amount). It will then update the price accordingly and display the original price with line through it and the discounted price. If no discounts associated, then just show the original price. By the way, the "size" is found in the table lunar_product_options and the value (1 oz, 10 ml, or 15 ml) is found in the table lunar_product_option_values.   --}}
                        <div id="quick-add" class="flex justify-between items-center mt-4 gap-3 text-sm text-coconuthusk">

                            {{-- Product Option : Size --}}

                            <div>
                                <select name="" id="" class="py-1.5 px-4 border-none rounded-2xl bg-coastalfern">
                                    <option value="1 oz">1 oz</option>
                                    <option value="15 ml">15 ml</option>
                                    <option value="10 ml">10 ml</option>
                                </select>
                            </div>

                            {{-- if there's discount associated, display %off --}}
                            <div class="bg-goldennut text-coconuthusk p-2 px-4 rounded-2xl font-bold">
                                20% off
                            </div>

                            <div id="price" class="flex items-center gap-2">
                                {{-- If there's a discount, then display the discounted price with line through the original price, otherwise just show the original price--}}

                                {{-- Original Price --}}
                                <div class="line-through text-red-500">
                                $50.00
                                </div>
                                {{-- discounted Price --}}
                                <div>
                                    $40.00
                                </div>
                                
                            </div>

                            {{-- Add to cart btn --}}
                            <div class="text-gray-500 hover:text-tamanuleaf">
                                <button class="relative inline-block">
                                    
                                    <x-heroicon-o-shopping-bag class="h-6 w-6" />
                                    <span class="sr-only">Add to cart</span>
                                    <span class="absolute bottom-0 left-1 flex items-center justify-center w-4 h-4 text-xs font-bold">+</span>
                                </button>
                                
                            </div>

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