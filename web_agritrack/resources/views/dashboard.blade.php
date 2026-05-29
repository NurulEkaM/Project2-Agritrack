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
            <span class="text-[10px] font-bold text-gray-400">Approved</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Total Pengeluaran</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-green-50 rounded-xl text-green-600">
                <i class="fas fa-chart-line"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400">Total</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Total Pemasukan</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-orange-50 rounded-xl text-orange-400">
                <i class="fas fa-receipt"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400">Records</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Jumlah Transaksi</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">{{ number_format($jumlahTransaksi, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-500">
                <i class="fas fa-calendar-check"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-400">{{ now()->format('M Y') }}</span>
        </div>
        <div>
            <p class="text-[10px] uppercase text-gray-400 font-extrabold tracking-wider mb-1">Pengeluaran Bulan Ini</p>
            <p class="text-xl font-extrabold text-gray-800 tracking-tight">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h3 class="font-bold text-gray-800 text-lg">Financial Trend</h3>
                <p class="text-xs text-gray-400">Analisis alur kas (6 Bulan Terakhir).</p>
            </div>
            <div class="bg-gray-50 px-3 py-1 rounded-lg">
                <span class="text-[10px] font-bold text-[#064E3B]">LIVE DATA</span>
            </div>
        </div>
        
        <div class="relative h-72 w-full">
            <canvas id="cashflowLineChart"></canvas>
        </div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <div class="flex justify-between items-center mb-8">
            <h3 class="font-bold text-gray-800">Recent Activity</h3>
            <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-history"></i></button>
        </div>

        <div class="space-y-8 flex-1">
            @forelse($recentActivity as $act)
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 {{ $act->tipe == 'pemasukan' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500' }} rounded-full flex items-center justify-center text-xs">
                        <i class="fas {{ $act->tipe == 'pemasukan' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">{{ Str::limit($act->nama, 18) }}</p>
                        <p class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($act->tanggal)->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-[10px] font-bold {{ $act->tipe == 'pemasukan' ? 'text-green-500' : 'text-red-500' }}">
                    {{ $act->tipe == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($act->nominal, 0, ',', '.') }}
                </p>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full py-10">
                <p class="text-center text-gray-400 text-xs">Belum ada aktivitas.</p>
            </div>
            @endforelse
        </div>

        <a href="{{ route('cashflow.page') }}" class="w-full py-4 mt-6 border-t border-gray-50 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-[#064E3B] transition">
            View All Activity
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('cashflowLineChart').getContext('2d');
        
        // Data dari Controller
        const chartData = @json($chartData);
        const labels = chartData.map(item => item.m.toUpperCase());
        const dataPemasukan = chartData.map(item => item.pemasukan);
        const dataPengeluaran = chartData.map(item => item.pengeluaran);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'PEMASUKAN',
                        data: dataPemasukan,
                        borderColor: '#064E3B',
                        backgroundColor: 'rgba(6, 78, 59, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#064E3B'
                    },
                    {
                        label: 'PENGELUARAN',
                        data: dataPengeluaran,
                        borderColor: '#FBC565',
                        backgroundColor: 'rgba(251, 197, 101, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#FBC565'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                return label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f9fafb' },
                        ticks: {
                            font: { size: 10 },
                            callback: function(value) {
                                if (value >= 1000) return 'Rp ' + (value/1000000) + 'jt';
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10, weight: 'bold' } }
                    }
                }
            }
        });
    });
</script>
@endsection