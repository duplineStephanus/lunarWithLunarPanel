<x-app-layout>
    {{-- use @include to show collection-show which will display featured products or sales --}}

    {{-- if user is logged in, use @include to show users frequently bought products or viewed or loved or recently added products--}}

    {{-- show product list using @include product-list.blade.php --}}
    <div class="py-12">
            <livewire:storefront.product-list />
    </div>

</x-app-layout>