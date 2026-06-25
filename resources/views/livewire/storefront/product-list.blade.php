<div class="bg-white">
    {{-- Product List --}}
    <div>

        <h1 class="flex items-center justify-center text-3xl font-bold tracking-tight text-tamanuleaf">Our Products </h1>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-wrap justify-center gap-x-6 gap-y-10">

                @foreach ($products as $product)

                    {{-- Injecting standard livewire component, assigning a clean distinct key tracking iteration context --}}
                    <livewire:storefront.product-card :product="$product" :key="'product-card-'.$product->id"/>

                @endforeach

            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $products->links() }}
            </div>

        </div>

    </div>

</div>