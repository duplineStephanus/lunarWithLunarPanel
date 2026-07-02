@php
    $description = html_entity_decode(strip_tags($this->product->translateAttribute('description') ?? ''));
    $shortDescription = \Illuminate\Support\Str::limit(trim($description), 500, '...');
@endphp

<div class="group relative mx-2 max-w-sm min-w-xs p-4 border rounded-xl transition-all duration-900 ease-in-out hover:shadow-lg hover:border-gray-400 bg-white">

    <a href="">
        {{-- Product Image --}}
        @if ($this->product->thumbnail)
            <img
                src="{{ $this->product->thumbnail->getUrl() }}"
                alt="{{ $this->product->translateAttribute('name') }}"
                class="aspect-square w-full rounded-md bg-gray-200 object-cover group-hover:bg-opacity-75 lg:h-80"
            >
        @else
            <div class="aspect-square w-full rounded-md bg-gray-100 lg:h-80"></div>
        @endif

        <div class="mt-4 flex justify-between gap-4">
            <div class="flex-1">
                {{-- Product Name --}}
                <h3 class="text-sm font-medium text-gray-900">
                    {{ $this->product->translateAttribute('name') }}
                </h3>

                {{-- Product Description --}}
                <p class="mt-1 text-sm text-gray-500">
                    {{ $shortDescription }}
                </p>
            </div>
        </div>
    </a>

    <div class="flex justify-between items-start mt-6">

        <div id="quick-add" class="space-y-3 ">
            
            @foreach($this->product->productOptions as $option)

                <div class="flex felx-col gap-0">

                   {{--  <label class="text-xs font-semibold text-gray-600">{{ $option->translate('name') }}</label> --}}

                    {{-- Livewire wire:model binding handles immediate reactivity on modification --}}
                    <select 
                        wire:model.live="selectedOptions.{{ $option->id }}" 
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-tamanuleaf sm:text-sm"
                    >

                        @foreach($option->values as $value)

                            <option value="{{ $value->id }}">
                                {{ $value->translate('name') }}
                            </option>

                        @endforeach

                    </select>
                </div>

            @endforeach

        </div>

        {{-- Dynamic Pricing Display --}}
        <div class="flex gap-4">
            
            {{-- Discount Badge --}}
            @if($discount)
                <div class="flex flex-col items-center gap-0">
                    <span class="text-sm font-bold text-red-600 bg-red-50 px-2 py-1 rounded">
                        @php
                            $discountData = $discount->data;
                        @endphp
                        
                        @if(isset($discountData['percentage']) && $discountData['percentage'])
                            {{ $discountData['percentage'] }}% OFF
                        @elseif(isset($discountData['fixed_value']) && $discountData['fixed_value'])
                            @php
                                $fixedValues = $discountData['fixed_values'] ?? [];
                                $discountAmount = $fixedValues['USD'] ?? array_values($fixedValues)[0] ?? 0;
                                $discountAmount = $discountAmount / 100;
                            @endphp
                            Save ${{ number_format($discountAmount, 2) }}
                        @endif
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $discount->name }}
                    </span>
                </div>
            @endif

            <div class="flex text-md gap-2">
                
                {{-- Original Price (with strikethrough if discounted) --}}
                <div class="font-semibold text-red-500 @if($discount) line-through @else text-tamanuleaf @endif ">
                    @if($currentPrice)
                        {{ $currentPrice->formatted() }}
                    @else
                        <span class="text-sm text-gray-400">Pricing unavailable</span>
                    @endif
                </div>

                {{-- Discounted Price (shown only if discount exists) --}}
                @if($discount && $discountedPrice)
                    <div class="font-semibold text-tamanuleaf">
                        ${{ number_format((float)$discountedPrice, 2) }}
                    </div>
                @endif

            </div>

        </div>

    </div>

    {{-- Add to cart btn --}}
    <div class="text-sm text-center bg-coastalfern/40 mt-6 border-none rounded-xl hover:bg-coastalfern overflow-hidden">
        <button 
            wire:click="addToCart"
            wire:loading.attr="disabled"
            wire:target="addToCart"
            id="add-to-cart"
            class="group w-full text-coconuthusk py-3  transition disabled:opacity-50"
        >
            <span wire:loading.remove wire:target="addToCart">Add to Cart</span>
            <span wire:loading wire:target="addToCart">Adding...</span>
            <span class="sr-only">Add to cart button</span>
        </button>
    </div>

</div>