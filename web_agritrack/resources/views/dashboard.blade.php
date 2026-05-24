@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Dashboard Overview</h1>
    <p class="text-sm text-gray-400">Monitoring Kiwari Farm operational health and financial flows.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-50 rounded-xl text-red-500">
                <i class="fas fa-chart-line transform rotate-180"></i>
            </div>
            <span class="text-xs font-bold text-red-400">-4.5%</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Total Pengeluaran</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">Rp 124.500.000</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-green-50 rounded-xl text-green-600">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="text-xs font-bold text-green-500">+12.2%</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Total Pemasukan</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">Rp 482.900.000</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-orange-50 rounded-xl text-orange-400">
                <i class="fas fa-receipt"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400">Total</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Jumlah Transaksi</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">1,429</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-500">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400">Bulan Ini</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Pengeluaran Bulan Ini</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">Rp 42.100.000</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Revenue vs Expenses</h3>
                <p class="text-xs text-gray-400">Comparison of operational costs against gross income.</p>
            </div>
            <div class="flex bg-gray-50 p-1 rounded-xl text-[10px] font-bold">
                <button class="px-4 py-2 bg-white shadow-sm rounded-lg text-[#064E3B]">6 Months</button>
                <button class="px-4 py-2 text-gray-400 hover:text-gray-600">1 Year</button>
            </div>
        </div>
        
        <div class="relative h-64 w-full flex items-end justify-between px-4">
            <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                <div class="border-b border-gray-50 w-full h-0"></div>
                <div class="border-b border-gray-50 w-full h-0"></div>
                <div class="border-b border-gray-50 w-full h-0"></div>
                <div class="border-b border-gray-100 w-full h-0"></div>
            </div>

            @php
                $data = [
                    ['m' => 'JAN', 'h1' => '60%', 'h2' => '30%'],
                    ['m' => 'FEB', 'h1' => '80%', 'h2' => '40%'],
                    ['m' => 'MAR', 'h1' => '70%', 'h2' => '55%'],
                    ['m' => 'APR', 'h1' => '90%', 'h2' => '35%'],
                    ['m' => 'MAY', 'h1' => '100%', 'h2' => '45%'],
                    ['m' => 'JUN', 'h1' => '85%', 'h2' => '60%'],
                ];
            @endphp

            @foreach($data as $item)
            <div class="relative flex flex-col items-center flex-1 group">
                <div class="flex items-end space-x-1.5 mb-2">
                    <div class="w-3.5 bg-[#064E3B] rounded-t-sm transition-all duration-500" style="height: {{ $item['h1'] }}"></div>
                    <div class="w-3.5 bg-[#FBC565] rounded-t-sm transition-all duration-500" style="height: {{ $item['h2'] }}"></div>
                </div>
                <span class="text-[10px] font-bold text-gray-400">{{ $item['m'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <div class="flex justify-between items-center mb-8">
            <h3 class="font-bold text-gray-800">Recent Activity</h3>
            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-h"></i></button>
        </div>

        <div class="space-y-8 flex-1">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-xs">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Office Supplies Restock</p>
                        <p class="text-[10px] text-gray-400">Paper, Toner, Ink cartridges</p>
                    </div>
                </div>
                <p class="text-[10px] font-bold text-red-500">- Rp 1.450.000</p>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center text-xs">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">Client Service Payment</p>
                        <p class="text-[10px] text-gray-400">PT Sejahtera Abadi - Hosting</p>
                    </div>
                </div>
                <p class="text-[10px] font-bold text-green-500">+ Rp 24.000.000</p>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-orange-50 text-orange-400 rounded-full flex items-center justify-center text-xs">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">New Employee Added</p>
                        <p class="text-[10px] text-gray-400">Sarah Johnson - UI Designer</p>
                    </div>
                </div>
                <p class="text-[10px] font-bold text-gray-400">System Task</p>
            </div>
        </div>

        <button class="w-full py-4 mt-6 border-t border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-[#064E3B] transition">
            View All Activity
        </button>
    </div>
</div>
@endsection