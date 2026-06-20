<x-app-layout>
    {{-- show collection-show which will display featured products or sales --}}

    @include('storefront.collection-show', ['collections' => $collections])

    {{-- show user's recently baught or recently added products--}}

    {{-- show product list using @include product-list.blade.php --}}
    <div class="py-12">
            <livewire:storefront.product-list />
    </div>

     {{-- show customer reviews previews --}}

</x-app-layout>