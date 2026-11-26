<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="font-medium capitalize">{{ str_replace('_', ' ', $order->status) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Order Date</p>
                        <p class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Delivery Address</p>
                        <p class="font-medium">{{ $order->delivery_address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">{{ $order->phone }}</p>
                    </div>
                </div>

                @if($order->notes)
                    <div class="mb-6">
                        <p class="text-sm text-gray-500">Notes</p>
                        <p>{{ $order->notes }}</p>
                    </div>
                @endif

                <h3 class="font-semibold mb-3">Items</h3>
                <table class="w-full mb-6">
                    <thead>
                        <tr class="border-b text-left text-sm text-gray-500">
                            <th class="pb-2">Product</th>
                            <th class="pb-2 text-center">Qty</th>
                            <th class="pb-2 text-right">Price</th>
                            <th class="pb-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b">
                                <td class="py-3">{{ $item->product_name }}</td>
                                <td class="py-3 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-3 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right space-y-1">
                    <p>Subtotal: Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                    <p>Delivery Fee: Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</p>
                    <p class="text-xl font-bold">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                </div>

                <div class="mt-6">
                    <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:underline">← Back to My Orders</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>