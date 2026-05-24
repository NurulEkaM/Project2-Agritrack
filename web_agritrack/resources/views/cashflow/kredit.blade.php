@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-[#064E3B] tracking-tight">Data Kredit</h1>
        <p class="text-sm text-gray-400">Manajemen catatan kredit dan hutang operasional.</p>
    </div>
    
    <button class="bg-[#fbc565] hover:bg-[#f9b233] text-black font-bold py-2 px-6 rounded-lg text-sm flex items-center shadow-sm transition">
        <a href="{{ route('kredit.create') }}" class="text-primary" style="text-decoration: none;">
            <i class="fas fa-plus mr-2"></i> Tambah Kredit
        </a>
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">ID</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Nama Kredit</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Jumlah (Saldo)</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($kredit as $item)
            <tr class="hover:bg-gray-50/50 transition">
                <td class="px-6 py-4 text-center text-sm font-bold text-gray-400">#{{ $item->id_kredit }}</td>
                <td class="px-6 py-4">
                    <p class="text-sm font-bold text-gray-800">{{ $item->nama }}</p>
                    <p class="text-[10px] text-gray-400 italic">Kategori: {{ $item->jenis_pengeluaran }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm font-bold text-red-500">Rp {{ number_format($item->saldo_kredit, 0, ',', '.') }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($item->status == 'setuju')
                        <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-[10px] font-black uppercase">Setuju</span>
                    @elseif($item->status == 'tunggu')
                        <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-[10px] font-black uppercase">Tunggu</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-black uppercase">Ditolak</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center space-x-2">
                        
                        {{-- Logika Edit: Hanya tampil jika status 'tunggu' --}}
                        @if($item->status == 'tunggu')
                            <a href="{{ route('admin.kredit.edit', $item->id_kredit) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                        @else
                            {{-- Ikon abu-abu/mati jika tidak bisa diedit (Opsional) --}}
                            <span class="p-2 text-gray-200 cursor-not-allowed">
                                <i class="fas fa-edit"></i>
                            </span>
                        @endif

                        {{-- Logika Hapus: Hanya tampil jika status 'tunggu' atau 'tidak disetuju' --}}
                        @if($item->status == 'tunggu' || $item->status == 'tidak disetuju')
                            <form action="{{ route('admin.kredit.destroy', $item->id_kredit) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus Data">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            {{-- Ikon abu-abu/mati jika tidak bisa dihapus --}}
                            <span class="p-2 text-gray-200 cursor-not-allowed">
                                <i class="fas fa-trash"></i>
                            </span>
                        @endif

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Data tidak ditemukan di database.</td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase">
        <span>Total {{ count($kredit) }} Data</span>
        <div class="flex gap-2">
            <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">Prev</button>
            <button class="px-3 py-1 bg-white border border-gray-200 rounded hover:bg-gray-100">Next</button>
        </div>
    </div>
</div>
@endsection