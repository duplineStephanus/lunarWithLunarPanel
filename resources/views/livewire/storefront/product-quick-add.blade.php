<div id="quick-add" class="flex justify-between items-center mt-4 gap-3 text-sm text-coconuthusk">

    {{-- Reactive Options Dropdown --}}
    <div>
        <select 
            wire:model.live="selectedOptionValueId" 
            class="py-1.5 px-4 border-none rounded-2xl bg-coastalfern text-gray-800 focus:outline-none"
        >
            @foreach($this->availableSizes as $size)
                <option value="{{ $size->id }}">
                    {{ $size->translate('name') }} {{-- --}}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Promotion Label Badge --}}
    @if($this->priceData['discount'])
        <div class="bg-goldennut text-coconuthusk p-2 px-4 rounded-2xl font-bold">
            {{ $this->priceData['discount'] }}
        </div>
    @endif

    {{-- Reactive Pricing Display Engine --}}
    <div id="price" class="flex items-center gap-2">
        @if($this->priceData['discount'])
            <div class="line-through text-red-500">
                ${{ number_format($this->priceData['original'], 2) }}
            </div>
        @endif
        
        <div class="text-gray-900 font-bold">
            ${{ number_format($this->priceData['current'], 2) }}
        </div>
    </div>

    {{-- Direct Add To Cart Execution --}}
    <div class="text-gray-500 hover:text-tamanuleaf">
        <button class="relative inline-block transition-transform active:scale-95">
            <x-heroicon-o-shopping-bag class="h-6 w-6" />
            <span class="sr-only">Add to cart</span>
            <span class="absolute bottom-0 left-1 flex items-center justify-center w-4 h-4 text-xs font-bold">+</span>
        </button>
    </div>

</div>