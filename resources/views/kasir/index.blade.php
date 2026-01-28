<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir POS</title>
    @vite('resources/css/app.css')
</head>
<body class="h-screen bg-gray-100 overflow-hidden">

<div class="flex h-full">

    <!-- LEFT : PRODUK -->
    <div class="w-2/3 p-4 overflow-y-auto">
        <h1 class="text-2xl font-bold mb-4">🧾 Kasir</h1>

        <!-- Search -->
        <input type="text"
               placeholder="Cari produk..."
               class="w-full mb-4 px-4 py-2 rounded-lg border focus:outline-none focus:ring">

        <!-- Produk Grid -->
        <div class="grid grid-cols-4 gap-4">
            @for ($i = 1; $i <= 8; $i++)
                <button class="bg-white rounded-xl shadow hover:shadow-lg p-4 text-center">
                    <div class="h-20 bg-gray-200 rounded mb-2"></div>
                    <p class="font-semibold">Produk {{ $i }}</p>
                    <p class="text-sm text-gray-500">Rp 10.000</p>
                </button>
            @endfor
        </div>
    </div>

    <!-- RIGHT : CART -->
    <div class="w-1/3 bg-white border-l flex flex-col">
        <div class="p-4 border-b">
            <h2 class="text-xl font-bold">🛒 Keranjang</h2>
        </div>

        <!-- Item -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-semibold">Produk A</p>
                    <p class="text-sm text-gray-500">1 x Rp 10.000</p>
                </div>
                <p class="font-bold">Rp 10.000</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t space-y-3">
            <div class="flex justify-between text-lg font-bold">
                <span>Total</span>
                <span>Rp 10.000</span>
            </div>

            <button class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl text-lg">
                💳 Bayar
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-xl">
                    Logout
                </button>
            </form>
        </div>
    </div>

</div>

</body>
</html>
