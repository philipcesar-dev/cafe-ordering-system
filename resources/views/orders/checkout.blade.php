<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Order Summary</h3>
                    
                    <ul class="mb-6 space-y-2">
                        @foreach($cart as $item)
                            <li class="flex justify-between text-sm">
                                <span>{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                                <span>Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="border-t pt-4 space-y-1">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Delivery Fee</span>
                            <span>Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span>Rp {{ number_format($total + $deliveryFee, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('orders.store') }}" method="POST" class="mt-8 space-y-4">
                        @csrf

                        <div>
                            <x-input-label for="delivery_address" value="Delivery Address" />
                            <textarea id="delivery_address" name="delivery_address" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                      required>{{ old('delivery_address') }}</textarea>
                            <x-input-error :messages="$errors->get('delivery_address')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Phone Number" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" 
                                          :value="old('phone')" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="notes" value="Notes (optional)" />
                            <textarea id="notes" name="notes" rows="2"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('cart.index') }}" class="px-4 py-2 bg-gray-200 rounded-md">Back to Cart</a>
                            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium">
                                Place Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>