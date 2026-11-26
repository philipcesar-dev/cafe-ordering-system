<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard') }}
            </h2>
            <span class="text-sm text-gray-500">Welcome, {{ Auth::user()->name }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['total_orders'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Pending Orders</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Products</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $stats['total_products'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Customers</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_users'] }}</p>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm text-gray-500">Revenue</p>
                    <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- ==================== WEATHER CARD ==================== --}}
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                            Current Weather
                        </h3>

                        @if(isset($weather) && $weather['success'])
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                                <div class="bg-sky-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Location</p>
                                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $weather['city'] }}</p>
                                </div>

                                <div class="bg-orange-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Temperature</p>
                                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $weather['temperature'] }}°C</p>
                                </div>

                                <div class="bg-blue-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Condition</p>
                                    <p class="text-lg font-semibold text-gray-800 mt-1 capitalize">{{ $weather['description'] }}</p>
                                </div>

                                <div class="bg-teal-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Humidity</p>
                                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $weather['humidity'] }}%</p>
                                </div>

                                <div class="bg-indigo-50 rounded-lg p-4 text-center">
                                    <p class="text-xs text-gray-500 uppercase tracking-wide">Wind Speed</p>
                                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $weather['wind_speed'] }} m/s</p>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <p class="font-medium">Weather information unavailable</p>
                                    <p class="text-sm">{{ $weather['message'] ?? 'Unable to fetch weather data at the moment.' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- ==================== END WEATHER CARD ==================== --}}

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('admin.products.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-lg text-center font-medium">
                    Manage Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center font-medium">
                    Manage Categories
                </a>
                <a href="{{ route('admin.orders.index') }}" class="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-lg text-center font-medium">
                    Manage Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-teal-600 hover:bg-teal-700 text-white p-4 rounded-lg text-center font-medium">
                    Manage Users
                </a>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>

                    @if($recentOrders->isEmpty())
                        <p class="text-gray-500">No orders yet.</p>
                    @else
                        <table class="w-full">
                            <thead>
                                <tr class="border-b text-left text-sm text-gray-500">
                                    <th class="pb-3">Order #</th>
                                    <th class="pb-3">Customer</th>
                                    <th class="pb-3">Status</th>
                                    <th class="pb-3 text-right">Total</th>
                                    <th class="pb-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr class="border-b">
                                        <td class="py-3 font-medium">{{ $order->order_number }}</td>
                                        <td class="py-3">{{ $order->user->name }}</td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        <td class="py-3 text-center">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline text-sm">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>