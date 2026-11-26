<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row gap-8">
                    <div class="md:w-1/2">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full rounded-lg">
                        @else
                            <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                    </div>
                    <div class="md:w-1/2">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                        <p class="text-gray-600 mt-2">{{ $product->description }}</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-4">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" 
                               class="mt-6 block text-center bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-md font-medium">
                                Login to Order
                            </a>
                        @endauth

                        <a href="{{ route('menu.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">
                            ← Back to Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>