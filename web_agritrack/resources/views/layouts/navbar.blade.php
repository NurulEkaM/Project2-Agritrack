<header class="bg-white border-b border-gray-50 px-8 py-4 flex justify-between items-center">
    <div class="flex flex-col">
        <h1 class="text-lg font-bold text-gray-800">
            Selamat Datang, <span class="text-[#065F46]">{{ Auth::user()->nama }}</span>!
        </h1>
        <p class="text-xs text-gray-400 capitalize">
            Role: {{ Auth::user()->role }}
        </p>
    </div>
    
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-gray-100 rounded-full border-2 border-[#065F46] flex items-center justify-center">
            <span class="text-sm font-bold text-[#065F46] uppercase">
                {{ substr(Auth::user()->nama, 0, 2) }}
            </span>
        </div>
    </div>
</header>