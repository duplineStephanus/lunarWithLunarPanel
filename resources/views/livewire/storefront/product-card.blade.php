@php
    $description = html_entity_decode(strip_tags($this->product->translateAttribute('description') ?? ''));
    $shortDescription = \Illuminate\Support\Str::limit(trim($description), 400, '...');
@endphp

<div class="group relative mx-2 max-w-sm min-w-xs p-4 border rounded-xl transition-all duration-900 ease-in-out hover:shadow-lg hover:border-gray-400 bg-white">

    <a href="{{ route('storefront.products.show', $this->product->defaultUrl?->slug) }}">
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

    <div class="flex justify-between items-end">

        {{-- Quick add UI --}}
        <div id="quick-add" class="mt-4 space-y-3">
            
            @foreach($this->product->productOptions as $option)

                <div class="flex flex-col gap-1">

                    <label class="text-xs font-semibold text-gray-600">{{ $option->translate('name') }}</label>

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
        <div class="mt-4 font-semibold text-lg text-tamanuleaf">
            @if($currentPrice)
                {{ $currentPrice->formatted() }}
            @else
                <span class="text-sm text-gray-400">Pricing unavailable</span>
            @endif
        </div>

    </div>

    

</div>