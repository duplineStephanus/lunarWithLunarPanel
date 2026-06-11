<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<!-- Include this script tag or install `@tailwindplus/elements` via npm: -->
<!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script> -->
<div class="bg-white">
  <!-- Mobile menu -->
  <el-dialog>
    <dialog id="mobile-menu" class="m-0 p-0 backdrop:bg-transparent lg:hidden">
      <el-dialog-backdrop class="fixed inset-0 bg-black/25 transition-opacity duration-300 ease-linear data-[closed]:opacity-0"></el-dialog-backdrop>
      <div tabindex="0" class="fixed inset-0 flex focus:outline focus:outline-0">
        <el-dialog-panel class="relative flex w-full max-w-xs transform flex-col overflow-y-auto bg-white pb-12 shadow-xl transition duration-300 ease-in-out data-[closed]:-translate-x-full">
          <div class="flex px-4 pb-2 pt-5">
            <button type="button" command="close" commandfor="mobile-menu" class="relative -m-2 inline-flex items-center justify-center rounded-md p-2 text-gray-400">
              <span class="absolute -inset-0.5"></span>
              <span class="sr-only">Close menu</span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
                <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>

          <!-- Profile / sign in -->
          @auth
            <x-dropdown-link :href="route('profile')" wire:navigate>
                {{ __('Profile') }}
            </x-dropdown-link>

            <!-- Authentication -->
            <button wire:click="logout" class="w-full text-start">
                <x-dropdown-link>
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </button>
            
          @else
            <div class="space-y-6 border-t border-gray-200 px-4 py-2">
              <div class="flow-root">
                <a href="/login" class="-m-2 block p-2 font-normal text-gray-500 hover:text-gray-700 hover:bg-gray-100">Sign in</a>
              </div>
              <div class="flow-root">
                <a href="/register" class="-m-2 block p-2 font-normal text-gray-500 hover:text-gray-700 hover:bg-gray-100">Create account</a>
              </div>
            </div>
          @endauth

          <div class="space-y-6 border-t border-gray-200 px-4 py-2">
            
            <div class="flow-root">
                <a href="#" class="-my-2 -mx-4 block py-2 px-4 font-normal text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-150">
                    About Us
                </a>
            </div>

            <div class="flow-root">
                <a href="#" class="-my-2 -mx-4 block py-2 px-4 font-normal text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-150">
                    Contact Us
                </a>
            </div>

            <div class="flow-root">
                <a href="#" class="-my-2 -mx-4 block py-2 px-4 font-normal text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition duration-150">
                    Our Blog
                </a>
            </div>
            
          </div>

        </el-dialog-panel>
        
      </div>
    </dialog>
  </el-dialog>

  <header class="relative bg-white">
    <p class="flex h-10 items-center justify-center bg-tamanuleaf px-4 text-sm font-medium text-white sm:px-6 lg:px-8">Get free delivery on orders over $100</p>

    <nav aria-label="Top" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="border-b border-gray-200">
        <div class="flex h-16 items-center">
          <!-- Mobile menu burger button -->
          <button type="button" command="show-modal" commandfor="mobile-menu" class="relative rounded-md bg-white p-2 text-gray-400 lg:hidden">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open menu</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6">
              <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>

          <!-- Logo -->
          <div class="ml-4 flex lg:ml-0">
            <a href="/">
              <span class="sr-only">Lana's Logo</span>
              <img src="https://duplinestephanus.github.io/WebbApp-Files/logo/alanas-logo.png" alt="" class="h-14 w-auto" />
            </a>
          </div>

          <div class="hidden m-6 lg:flex lg:flex-1 lg:items-center lg:justify-start lg:space-x-6">
            <a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">About Us</a>
            <a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">Contact Us</a>
            <a href="#" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">Our Blog</a>
          </div>

          <div class="ml-auto flex items-center">
            <!-- Search -->
            <div class="flex lg:ml-6">
              <button type="button" wire:click="$dispatchTo('components.product-search', 'openSearch')" class="p-2 text-gray-400 hover:text-tamanu-leaf transition">
                <span class="sr-only">Search Products</span>
                <x-heroicon-o-magnifying-glass class="h-6 w-6"/>
              </button>
            </div>
            
            @auth
              <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            @else
              <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-end lg:space-x-6">
                <a href="/login" class="text-sm font-medium text-gray-700 hover:text-gray-800">Sign in</a>
                <span aria-hidden="true" class="h-6 w-px bg-gray-200"></span>
                <a href="/register" class="text-sm font-medium text-gray-700 hover:text-gray-800">Create account</a>
              </div>
            @endauth

            <!-- Cart -->
            <div class="ml-4 flow-root lg:ml-6">
              <a href="#" class="group -m-2 flex items-center p-2 text-gray-500">
                <x-heroicon-o-shopping-bag class="h-6 w-6 hover:text-gray-700" />
                <span id="cart-total" class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700">0</span>
                <span class="sr-only">items in cart, view bag</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>
</div>
