<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(empty($cart))
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8 text-center">
                    <p class="text-gray-500 text-lg">Your cart is empty.</p>
                    <a href="{{ route('menu.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">
                        Browse Menu →
                    </a>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form action="{{ route('cart.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-3">Product</th>
                                        <th class="text-center py-3">Price</th>
                                        <th class="text-center py-3">Qty</th>
                                        <th class="text-right py-3">Subtotal</th>
                                        <th class="text-center py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $id => $item)
                                        <tr class="border-b">
                                            <td class="py-4">
                                                <div class="font-medium">{{ $item['name'] }}</div>
                                            </td>
                                            <td class="text-center">
                                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <input type="number" 
                                                       name="quantity[{{ $id }}]" 
                                                       value="{{ $item['quantity'] }}" 
                                                       min="1" 
                                                       class="w-16 border-gray-300 rounded-md text-center">
                                            </td>
                                            <td class="text-right font-medium">
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('cart.remove', $id) }}" 
                                                   onclick="event.preventDefault(); document.getElementById('remove-{{ $id }}').submit();"
                                                   class="text-red-600 hover:underline text-sm">
                                                    Remove
                                                </a>
                                                <form id="remove-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="mt-6 flex justify-between items-center">
                                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                                    Update Cart
                                </button>

                                <div class="text-right">
                                    <p class="text-xl font-bold">
                                        Total: Rp {{ number_format($total, 0, ',', '.') }}
                                    </p>
                                    <a href="{{ route('checkout') }}" 
                                       class="mt-3 inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-medium">
                                        Proceed to Checkout
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>