<div class="bg-white">
  <div class="pt-6">
    @if (session()->has('message'))
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-4">
        <div class="rounded-md bg-green-50 p-4 text-sm font-medium text-green-800">
          {{ session('message') }}
        </div>
      </div>
    @endif

    <nav aria-label="Breadcrumb">
      <ol role="list" class="mx-auto flex max-w-2xl items-center space-x-2 px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <li>
          <div class="flex items-center">
            <a href="/" class="mr-2 text-sm font-medium text-gray-900">Home</a>
            <svg viewBox="0 0 16 20" width="16" height="20" fill="currentColor" aria-hidden="true" class="h-5 w-4 text-gray-300">
              <path d="M5.697 4.34L8.98 16.532h1.327L7.025 4.341H5.697z" />
            </svg>
          </div>
        </li>
        <li class="text-sm">
          <a href="#" aria-current="page" class="font-medium text-gray-500 hover:text-gray-600">
            {{ $product->translateAttribute('name') }}
          </a>
        </li>
      </ol>
    </nav>

    <div class="mx-auto mt-6 max-w-2xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:gap-8 lg:px-8">
      @php $images = $product->getMedia('images'); @endphp
      
      @if($images->get(0))
        <img src="{{ $images->get(0)->getUrl('large') ?? $images->get(0)->getUrl() }}" alt="Featured view" class="row-span-2 aspect-3/4 size-full rounded-lg object-cover max-lg:hidden" />
      @endif
      
      @if($images->get(1))
        <img src="{{ $images->get(1)->getUrl() }}" alt="Secondary view" class="col-start-2 aspect-3/2 size-full rounded-lg object-cover max-lg:hidden" />
      @endif
      
      @if($images->get(2))
        <img src="{{ $images->get(2)->getUrl() }}" alt="Tertiary view" class="col-start-2 row-start-2 aspect-3/2 size-full rounded-lg object-cover max-lg:hidden" />
      @endif
      
      @if($images->first())
        <img src="{{ $images->first()->getUrl() }}" alt="Mobile view" class="row-span-2 aspect-4/5 size-full object-cover sm:rounded-lg lg:aspect-3/4" />
      @endif
    </div>

    <div class="mx-auto max-w-2xl px-4 pt-10 pb-16 sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-3 lg:grid-rows-[auto_auto_1fr] lg:gap-x-8 lg:px-8 lg:pt-16 lg:pb-24">
      <div class="lg:col-span-2 lg:border-r lg:border-gray-200 lg:pr-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
          {{ $product->translateAttribute('name') }}
        </h1>
      </div>

      <div class="mt-4 lg:row-span-3 lg:mt-0">
        <h2 class="sr-only">Product information</h2>
        <p class="text-3xl tracking-tight text-gray-900">
          @if($selectedVariant)
            {{ $selectedVariant->pricing()->get()->first()?->price->formatted() }}
          @else
            {{ $product->prices->first()?->price->formatted() }}
          @endif
        </p>

        <form wire:submit.prevent="addToBag" class="mt-10">
          
          {{-- Dynamic Matrix Mapping of Variant Choices --}}
          @foreach($product->variants->pluck('options')->flatten(1)->groupBy('handle') as $handle => $options)
            <div class="mt-10">
              <h3 class="text-sm font-medium text-gray-900 capitalize">{{ $handle }}</h3>
              <fieldset aria-label="Choose a {{ $handle }}" class="mt-4">
                <div class="flex items-center gap-x-3">
                  @foreach($options->unique('value.name') as $option)
                    <label class="group relative flex items-center justify-center rounded-md border border-gray-300 bg-white p-3 cursor-pointer has-checked:border-indigo-600 has-checked:bg-indigo-600 has-focus-visible:outline-2 has-focus-visible:outline-offset-2 focus:outline-none">
                      <input type="radio" 
                             wire:model.live="variantSelections.{{ $handle }}" 
                             value="{{ $option->value->name }}" 
                             class="absolute inset-0 appearance-none cursor-pointer focus:outline-none" />
                      <span class="text-sm font-medium text-gray-900 uppercase group-has-checked:text-white">
                        {{ $option->value->name }}
                      </span>
                    </label>
                  @endforeach
                </div>
              </fieldset>
            </div>
          @endforeach

          <div class="mt-6">
            <label for="quantity" class="text-sm font-medium text-gray-900">Quantity</label>
            <input type="number" id="quantity" wire:model="quantity" min="1" class="mt-2 block w-20 rounded-md border-gray-300 p-2 border shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
          </div>

          <button type="submit" 
                  wire:loading.attr="disabled"
                  class="mt-10 flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden disabled:opacity-50">
            <span wire:loading.remove wire:target="addToBag">Add to bag</span>
            <span wire:loading wire:target="addToBag">Processing...</span>
          </button>
        </form>
      </div>

      <div class="py-10 lg:col-span-2 lg:col-start-1 lg:border-r lg:border-gray-200 lg:pt-6 lg:pr-8 lg:pb-16">
        <div>
          <h3 class="sr-only">Description</h3>
          <div class="space-y-6 text-base text-gray-900">
            {!! $product->translateAttribute('description') !!}
          </div>
        </div>
      </div>

    </div>
  </div>
</div>