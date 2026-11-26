<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Café Luxe - Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(180deg, #2c1810 0%, #1a0f0a 100%);
            min-height: 100vh;
        }
        .card-glow {
            background: linear-gradient(145deg, #3d2317, #2a1810);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .gold { color: #e8b923; }
        .gold-bg { background-color: #e8b923; }
        .gold-border { border-color: #e8b923; }
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #e8b923;
            border-radius: 50%;
            opacity: 0.3;
            animation: float 6s infinite ease-in-out;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); opacity: 0.2; }
            50% { transform: translateY(-20px); opacity: 0.5; }
        }
    </style>
</head>
<body class="text-white relative overflow-x-hidden">

    {{-- Floating particles --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        @for($i = 0; $i < 15; $i++)
            <div class="particle" style="left: {{ rand(5,95) }}%; top: {{ rand(10,90) }}%; animation-delay: {{ $i * 0.4 }}s;"></div>
        @endfor
    </div>

    {{-- Navbar --}}
    <nav class="relative z-20 border-b border-yellow-900/40">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-2xl font-bold gold">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 21h18v-2H2v2zm6-4h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H8c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2zm0-12h10v10H8V5z"/>
                    <path d="M18 7h2c1.1 0 2 .9 2 2v4c0 1.1-.9 2-2 2h-2V7z"/>
                </svg>
                Café Luxe
            </a>

            {{-- Center Nav --}}
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="gold border-b-2 border-yellow-500 pb-1">Menu</a>
                <a href="{{ route('orders.index') }}" class="text-gray-300 hover:text-yellow-400 transition">My Orders</a>
                <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:text-yellow-400 transition">Profile</a>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-5">
                <span class="text-sm text-gray-300">{{ Auth::user()->name ?? 'Customer' }}</span>
                <a href="{{ route('cart.index') }}" class="relative">
                    <svg class="w-7 h-7 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <div class="relative z-10 text-center pt-16 pb-10 px-4">
        <h1 class="text-5xl md:text-6xl font-bold gold mb-3">Good morning!</h1>
        <p class="text-gray-400 text-lg">What would you like to order today?</p>
    </div>

    {{-- Category Filters --}}
    <div class="relative z-10 flex flex-wrap justify-center gap-3 px-4 mb-12">
        <a href="{{ route('home') }}"
           class="px-5 py-2 rounded-full text-sm font-medium transition
                  {{ !request('category') ? 'gold-bg text-brown-900 font-semibold' : 'bg-[#3d2317] text-gray-300 hover:bg-[#4a2c1c]' }}">
            All
        </a>
        @foreach($categories as $category)
            <a href="{{ route('home', ['category' => $category->id]) }}"
               class="px-5 py-2 rounded-full text-sm font-medium transition
                      {{ request('category') == $category->id ? 'gold-bg text-brown-900 font-semibold' : 'bg-[#3d2317] text-gray-300 hover:bg-[#4a2c1c]' }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Products Grid --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 pb-20">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                @foreach($category->products->where('is_available', true) as $product)
                    @if(!request('category') || request('category') == $category->id)
                        <div class="card-glow rounded-2xl overflow-hidden flex flex-col">
                            {{-- Icon area --}}
                            <div class="h-40 flex items-center justify-center bg-gradient-to-b from-[#4a2c1c]/50 to-transparent">
                                {{-- Simple coffee/tea icon based on category --}}
                                @if(str_contains(strtolower($category->name), 'tea'))
                                    <svg class="w-16 h-16 text-yellow-200/80" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
                                        <path d="M6 1v3M10 1v3M14 1v3" stroke="currentColor" stroke-width="2" fill="none"/>
                                    </svg>
                                @else
                                    <svg class="w-16 h-16 text-yellow-200/80" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-lg font-semibold text-white">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-400 mt-1 mb-4 line-clamp-2">{{ $product->description }}</p>

                                <div class="mt-auto flex items-center justify-between">
                                    <span class="text-xl font-bold gold">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>

                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-1.5 rounded-full border gold-border text-sm gold hover:bg-yellow-500 hover:text-brown-900 transition font-medium">
                                            + Add
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach
        </div>
    </div>

</body>
</html>