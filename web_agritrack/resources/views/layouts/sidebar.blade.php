<aside class="w-64 bg-white border-r border-gray-100 flex flex-col">
    <div class="p-6">
        <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-22 mx-auto">
    </div>

    <nav class="flex-1 px-4 space-y-2 mt-4">
        <a href="/dashboard" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('dashboard') ? 'bg-[#065F46] text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50' }}">
            <i class="fas fa-th-large w-5"></i>
            <span class="text-sm font-semibold">Dashboard</span>
        </a>

        <a href="{{ route('cashflow.page') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('cashflow*') ? 'bg-[#065F46] text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50' }}">
            <i class="fas fa-wallet w-5"></i>
            <span class="text-sm font-semibold">Cashflow</span>
        </a>

        <a href="#" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('transactions*') ? 'bg-[#065F46] text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50' }}">
            <i class="fas fa-exchange-alt w-5"></i>
            <span class="text-sm font-semibold">Transactions</span>
        </a>

        <a href="{{ route('absensi.page') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('absensi*') ? 'bg-[#065F46] text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50' }}">
            <i class="fas fa-users w-5"></i>
            <span class="text-sm font-semibold">Absensi</span>
        </a>

        <a href="{{ route('gaji.page') }}" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('gaji*') ? 'bg-[#065F46] text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50' }}">
            <i class="fas fa-cog w-5"></i>
            <span class="text-sm font-semibold">Gaji</span>
        </a>
{{-- 
        <a href="#" 
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition {{ Request::is('profile*') ? 'bg-[#065F46] text-white shadow-sm' : 'text-gray-400 hover:bg-gray-50' }}">
            <i class="fas fa-user w-5"></i>
            <span class="text-sm font-semibold">Profile</span>
        </a> --}}

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <a href="#" 
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-red-400 hover:bg-red-50 mt-10">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span class="text-sm font-semibold">Logout</span>
        </a>

    </nav>
</aside>