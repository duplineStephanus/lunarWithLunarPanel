<x-app-layout>

    <div class="space-y-12">

        @foreach($collections as $collection)

            @if($collection->products->isNotEmpty())

                <div class="bg-white">

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

</x-app-layout>