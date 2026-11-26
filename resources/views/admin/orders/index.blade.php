<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">All Orders</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filter --}}
            <div class="mb-4 flex gap-2 flex-wrap">
                <a href="{{ route('admin.orders.index') }}" 
                   class="px-3 py-1 rounded-full text-sm {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
                    All
                </a>
                @foreach(['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'] as $status)
                    <a href="{{ route('admin.orders.index', ['status' => $status]) }}" 
                       class="px-3 py-1 rounded-full text-sm {{ request('status') === $status ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $order->order_number }}</td>
                                <td class="px-6 py-4">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm">View / Update</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $orders->links() }}</div>
        </div>
    </div>
</x-app-layout>