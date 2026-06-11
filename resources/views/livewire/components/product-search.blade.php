<div class="relative z-50">
    <div x-data="{ open: @entangle('isOpen') }" 
         x-show="open" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500/25 backdrop-blur-sm transition-opacity" 
         style="display: none;">
        
        <div class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20">
            <div x-on:click.away="open = false" 
                 class="mx-auto max-w-xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5 transition-all">
                
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400" />
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" 
                           placeholder="Search our organic skin collections..." 
                           autofocus />
                </div>

                @if(strlen($search) >= 2)
                    <ul class="max-h-72 scroll-py-2 overflow-y-auto py-2 text-sm text-gray-800">
                        @forelse($results as $product)
                            <li class="group select-none px-4 py-2 hover:bg-gray-50 transition">
                                <a href="/products/{{ $product->defaultUrl?->slug }}" class="flex items-center space-x-4">
                                    <div class="h-10 w-10 shrink-0 overflow-hidden rounded bg-gray-100">
                                        @if($product->thumbnail)
                                            <img src="{{ $product->thumbnail->getUrl() }}" alt="" class="h-full w-full object-cover" />
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-gray-300"><x-heroicon-o-photo class="w-5 h-5" /></div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 group-hover:text-tamanu-leaf transition truncate">
                                            {{ $product->translateAttribute('name') }}
                                        </p>
                                        <p class="text-xs text-gray-400 truncate">
                                            {!! \Illuminate\Support\Str::limit(html_entity_decode(strip_tags($product->translateAttribute('description'))), 60, '...') !!}
                                        </p>
                                    </div>

                                    @php
                                        $price = $product->variants->first()?->prices->first();
                                    @endphp
                                    @if($price)
                                        <span class="text-xs font-semibold text-tamanu-leaf">{{ $price->price->formatted() }}</span>
                                    @endif
                                </a>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-center text-sm sm:px-14">
                                <x-heroicon-o-exclamation-circle class="mx-auto h-6 w-6 text-gray-400" />
                                <p class="mt-4 font-semibold text-gray-900">No products found</p>
                                <p class="mt-2 text-gray-500">We couldn't find anything matching "{{ $search }}".</p>
                            </li>
                        @endforelse
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>