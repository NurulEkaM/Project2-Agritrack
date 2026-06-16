<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kiwari Farm Indonesia - Perkebunan Buah Naga Kuning Golden & Agro-Edutourism</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.45)), url("{{ asset('img/maxresdefault.jpg') }}");
      background-size: cover;
      background-position: center;
    }
    .cta-section {
      background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url("{{ asset('img/hq720.jpg') }}");
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">

  <div class="bg-green-700 text-white text-xs py-2.5 px-6 hidden sm:flex justify-between items-center border-b border-green-600/50">
    <div class="flex items-center gap-6">
      <span class="flex items-center gap-1.5 opacity-90">
        <i class="fas fa-map-marker-alt text-orange-400"></i> Subang, Jawa Barat, Indonesia
      </span>
      <span class="flex items-center gap-1.5 opacity-90">
        <i class="fas fa-clock text-orange-400"></i> Jam Operasional: 07:00 - 16:00 WIB
      </span>
    </div>
    <div class="flex items-center gap-5 text-[11px] font-medium tracking-wide">
      <a href="#" class="hover:text-orange-400 transition-colors duration-200">FAQ</a>
      <span class="text-green-500">|</span>
      <a href="#" class="hover:text-orange-400 transition-colors duration-200">Bantuan</a>
      <span class="text-green-500">|</span>
      <a href="#" class="hover:text-orange-400 transition-colors duration-200">Hubungan Investor</a>
    </div>
  </div>

  <nav class="bg-white/95 backdrop-blur-md sticky top-0 z-50 py-3 shadow-sm border-b border-gray-100">
    <div class="container mx-auto px-4 xl:px-6 flex justify-between items-center">
      
      <a href="#" class="flex items-center shrink-0">
        <img src="{{ asset('img/Logo.png') }}" alt="Logo Kiwari Farm" class="h-12 md:h-14 lg:h-16 w-auto object-contain">
      </a>
      
      <ul class="hidden lg:flex items-center xl:space-x-8 lg:space-x-4 text-[13px] font-bold uppercase tracking-wider text-gray-700">
        <li class="relative group">
          <a href="#" class="text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Home</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-100 transition-transform duration-300"></span>
        </li>
        <li class="relative group">
          <a href="#about" class="hover:text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Tentang Kami</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </li>
        <li class="relative group">
          <a href="#features" class="hover:text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Keunggulan</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </li>
        <li class="relative group">
          <a href="#products" class="hover:text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Katalog Produk</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </li>
        <li class="relative group">
          <a href="#process" class="hover:text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Alur Kerja</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </li>
        <li class="relative group">
          <a href="#stories" class="hover:text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Kiwari Stories</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </li>
        <li class="relative group">
          <a href="#news" class="hover:text-green-600 transition-colors duration-200 block py-2 whitespace-nowrap">Artikel Edukasi</a>
          <span class="absolute bottom-0 left-0 w-full h-0.5 bg-green-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
        </li>
      </ul>

      <div class="hidden sm:flex items-center lg:gap-5 md:gap-3 shrink-0">
        <a href="/login" class="bg-green-600 text-white lg:px-5 lg:py-2.5 md:px-4 md:py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider hover:bg-green-700 active:scale-95 transition-all duration-200 shadow-md hover:shadow-green-600/20 flex items-center gap-2 whitespace-nowrap">
          <i class="fas fa-sign-in-alt text-sm"></i> Login
        </a>
      </div>

      <button class="lg:hidden p-2 text-2xl text-gray-700 hover:bg-gray-50 rounded-lg transition-colors focus:outline-none">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </nav>

  <section class="hero-section h-[800px] md:h-[900px] flex items-center relative text-white">
    <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-transparent to-black/20"></div>
    <div class="container mx-auto px-6 z-10">
      <div class="max-w-3xl">
        <span class="inline-flex items-center gap-2 bg-green-500/20 text-green-300 font-bold uppercase tracking-widest text-xs px-4 py-2 rounded-full mb-6 border border-green-500/30">
          <i class="fas fa-seedling"></i> Pelopor Buah Naga Kuning Golden di Indonesia
        </span>
        <h1 class="text-5xl sm:text-7xl md:text-8xl font-serif font-black mb-6 italic leading-none">
          Agriculture <br><span class="text-green-400 font-sans not-italic font-bold">Kiwari Fram</span>
        </h1>
        <p class="text-base sm:text-xl mb-10 text-gray-200 leading-relaxed max-w-2xl">
          Sentra budidaya dan penyedia bibit buah naga kuning Golden terbesar serta terpercaya di Indonesia. Dikembangkan langsung secara ekologis di dataran subur Purwadadi, Subang.
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="#products" class="bg-green-500 hover:bg-green-600 text-white px-10 py-4 rounded-xl font-bold transition text-center shadow-xl tracking-wide text-base">
            Explore Marketplace <i class="fas fa-arrow-right ml-2"></i>
          </a>
          <a href="#about" class="bg-white/10 backdrop-blur-sm border-2 border-white/40 hover:bg-white hover:text-gray-900 px-10 py-4 rounded-xl font-bold transition text-center text-base">
            Kunjungi Kebun Kami
          </a>
        </div>
      </div>
    </div>

    <div class="absolute -bottom-32 left-0 right-0 hidden lg:flex justify-center container mx-auto px-6 z-20">
      <div class="bg-white grid grid-cols-3 gap-0 rounded-2xl shadow-2xl border border-gray-100 overflow-hidden w-full max-w-6xl">
        <div class="p-8 border-r border-gray-100 flex gap-5 items-start hover:bg-gray-50 transition">
          <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 text-2xl shrink-0">
            <i class="fas fa-award"></i>
          </div>
          <div>
            <h3 class="font-bold text-lg text-gray-900 mb-2">Pelopor Golden Pitaya</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Perkebunan pertama sekaligus kiblat utama budidaya bibit buah naga kuning jenis Golden di nusantara.</p>
          </div>
        </div>
        <div class="p-8 border-r border-gray-100 flex gap-5 items-start hover:bg-gray-50 transition">
          <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-orange-500 text-2xl shrink-0">
            <i class="fas fa-seedling"></i>
          </div>
          <div>
            <h3 class="font-bold text-lg text-gray-900 mb-2">Produsen Bibit Terbesar</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Penyedia bibit buah naga kuning dan merah berkualitas unggul, sehat, serta bergaransi validitas varietas.</p>
          </div>
        </div>
        <div class="p-8 flex gap-5 items-start hover:bg-gray-50 transition">
          <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-2xl shrink-0">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div>
            <h3 class="font-bold text-lg text-gray-900 mb-2">Edukasi Agro-Tourism</h3>
            <p class="text-sm text-gray-500 leading-relaxed">Membuka pintu wawasan bagi instansi, mahasiswa, dan kelompok tani untuk belajar teknik budidaya modern.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="about" class="pt-56 pb-24 bg-white">
    <div class="container mx-auto px-6 grid lg:grid-cols-2 gap-20 items-center">
      <div class="relative flex justify-center lg:justify-start">
        <div class="w-[340px] h-[340px] sm:w-[500px] sm:h-[500px] rounded-2xl overflow-hidden border-8 border-gray-50 shadow-2xl transform rotate-2">
          <img src="{{ asset('img/hq720.jpg') }}" class="w-full h-full object-cover" alt="Kiwari Farm Activity">
        </div>
        <div class="absolute -bottom-10 -right-4 lg:right-10 w-44 h-44 sm:w-64 sm:h-64 rounded-2xl overflow-hidden border-8 border-white shadow-2xl transform -rotate-6">
          <img src="{{ asset('img/image.png') }}" class="w-full h-full object-cover" alt="Organic Harvest">
        </div>
      </div>
      
      <div>
        <span class="text-orange-500 font-extrabold uppercase tracking-widest text-xs block mb-2">Siapa Kami?</span>
        <h2 class="text-3xl sm:text-5xl font-black text-gray-900 mb-6 uppercase tracking-tight leading-tight">Kiwari Farm Indonesia</h2>
        <p class="text-green-600 font-bold mb-6 italic text-xl">Dusun Hegarmanah Purwadadi Rt/Rw 023, 006, Kabupaten Subang, Jawa Barat 41261</p>
        <p class="text-gray-600 mb-8 leading-relaxed text-justify text-base">
          Kiwari Farm adalah perkebunan buah naga kuning Golden pertama di Indonesia dan Kiwari Farm penyedia bibit buah naga kuning terbesar dan terpercaya dan kami juga menjual bibit buah naga merah. Kami berfokus pada standarisasi pertanian modern yang mampu mendistribusikan kualitas varietas buah premium ke seluruh pelosok tanah air.
        </p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:shadow-sm transition">
            <i class="fas fa-dna text-orange-500 text-3xl"></i>
            <div>
              <h4 class="font-bold text-gray-800 text-sm">Akurasi Varietas Valid</h4>
              <p class="text-xs text-gray-400">Indukan super asli non-hibrida acak</p>
            </div>
          </div>
          <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:shadow-sm transition">
            <i class="fas fa-star text-orange-500 text-3xl"></i>
            <div>
              <h4 class="font-bold text-gray-800 text-sm">Rasa & Kualitas Premium</h4>
              <p class="text-xs text-gray-400">Tingkat kemanisan buah (Brix) tinggi</p>
            </div>
          </div>
        </div>

        <div class="space-y-4 mb-10">
          <div class="flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
            <p class="text-gray-700 text-sm"><strong class="text-gray-900">Spesialisasi Budidaya Khusus:</strong> Menguasai teknik perawatan intensif struktur batang tanaman kaktus buah naga kuning agar produktivitas panen melimpah.</p>
          </div>
          <div class="flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl mt-0.5"></i>
            <p class="text-gray-700 text-sm"><strong class="text-gray-900">Jaminan Kualitas Bibit:</strong> Batang stek yang kami pasarkan telah melalui fase karantina ketat, bebas jamur, serta siap tanam untuk wilayah tropis.</p>
          </div>
        </div>
        <a href="#" class="inline-block bg-orange-500 text-white px-10 py-3.5 rounded-xl font-bold hover:bg-orange-600 transition shadow-lg text-sm">Pelajari Visi Misi Kami</a>
      </div>
    </div>
  </section>

  <section id="features" class="py-24 bg-gray-50 border-y border-gray-100">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-20">
        <span class="text-green-600 font-extrabold uppercase tracking-widest text-xs">Pilar Perkebunan</span>
        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mt-2 uppercase">Keunggulan Utama Kebun Kiwari</h2>
        <div class="h-1 w-20 bg-green-500 mx-auto mt-4"></div>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-xl hover:-translate-y-2 transition duration-300">
          <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-2xl mb-6 group-hover:bg-green-600 group-hover:text-white transition duration-300">
            <i class="fas fa-certificate"></i>
          </div>
          <h3 class="font-bold text-xl text-gray-900 mb-3">Varietas Unggul</h3>
          <p class="text-sm text-gray-500 leading-relaxed text-justify">Pengembangan genetika varietas bibit buah naga kuning Golden pilihan yang adaptif terhadap karakteristik tanah di Indonesia.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-xl hover:-translate-y-2 transition duration-300">
          <div class="w-14 h-14 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 text-2xl mb-6 group-hover:bg-orange-500 group-hover:text-white transition duration-300">
            <i class="fas fa-shuttle-space"></i>
          </div>
          <h3 class="font-bold text-xl text-gray-900 mb-3">Agroteknologi Tepat</h3>
          <p class="text-sm text-gray-500 leading-relaxed text-justify">Menerapkan sistem pemupukan makro-mikro organik seimbang guna memicu pembunguan buah secara berkala dan optimal.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-xl hover:-translate-y-2 transition duration-300">
          <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-2xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition duration-300">
            <i class="fas fa-truck"></i>
          </div>
          <h3 class="font-bold text-xl text-gray-900 mb-3">Pengiriman Bibit Aman</h3>
          <p class="text-sm text-gray-500 leading-relaxed text-justify">Pengepakan bibit stek menggunakan pengaman berlapis untuk menjaga kelembapan batang selama perjalanan logistik jarak jauh.</p>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 group hover:shadow-xl hover:-translate-y-2 transition duration-300">
          <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center text-yellow-600 text-2xl mb-6 group-hover:bg-yellow-600 group-hover:text-white transition duration-300">
            <i class="fas fa-chalkboard-user"></i>
          </div>
          <h3 class="font-bold text-xl text-gray-900 mb-3">Bimbingan Intensif</h3>
          <p class="text-sm text-gray-500 leading-relaxed text-justify">Kami memberikan layanan konsultasi gratis pasca-pembelian bibit demi keberhasilan investasi perkebunan mandiri Anda.</p>
        </div>
      </div>
    </div>
  </section>

  <section id="products" class="py-24 bg-white">
    <div class="container mx-auto px-6">
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-16">
        <div>
          <span class="text-orange-500 font-extrabold uppercase tracking-widest text-xs">Kiwari Marketplace</span>
          <h2 class="text-3xl md:text-5xl font-black text-gray-900 uppercase mt-2 tracking-tight">Katalog Produk Unggulan</h2>
        </div>
      </div>
      <div class="flex overflow-x-auto pb-4 gap-6 scrollbar-thin scroll-smooth snap-x snap-mandatory">
        @if(isset($produk) && $produk->count() > 0)
            @foreach($produk as $item)
            <div class="w-[280px] sm:w-[320px] shrink-0 snap-start bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col justify-between group">
                <div class="relative overflow-hidden">
                @if(isset($item->diskon) && $item->diskon > 0)
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-[11px] font-black tracking-widest uppercase px-3 py-1 rounded-full z-10 shadow-md">
                    {{ $item->diskon }}% Promo
                    </span>
                @endif
                
                <span class="absolute top-4 right-4 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-3 py-1 rounded-md z-10 uppercase tracking-wider">
                    {{ $item->kategori->nama ?? 'Kiwari Farm' }}
                </span>
                
                <div class="h-60 bg-gray-50 overflow-hidden">
                    <img src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('img/image.png') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $item->nama_produk }}">
                </div>
                </div>
                
                <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <div class="flex text-yellow-400 text-xs mb-2">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <span class="text-gray-400 text-[10px] ml-1 font-medium">(5.0)</span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 mb-1 leading-tight group-hover:text-green-600 transition duration-200 line-clamp-1">
                    {{ $item->nama_produk }}
                    </h3>
                    <p class="text-xs text-gray-400 mb-4 line-clamp-2">
                    {{ $item->deskripsi ?? 'Produk perkebunan asli berkualitas tinggi dari Kiwari Farm Subang, Jawa Barat.' }}
                    </p>
                </div>
                
                <div class="pt-4 border-t border-gray-50">
                    <div class="flex justify-between items-baseline mb-4">
                    <div>
                        <span class="text-2xl font-black text-gray-950 tracking-tight">
                        Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-gray-400 font-bold"> / {{ $item->satuan ?? 'pcs' }}</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 bg-green-50 text-green-700 rounded-md">
                        Stok: {{ $item->stok ?? '0' }}
                    </span>
                    </div>
                    <a href="https://linktr.ee/kiwarimart.dedi?utm_source=ig&utm_medium=social&utm_content=link_in_bio&fbclid=PAZXh0bgNhZW0CMTEAc3J0YwZhcHBfaWQPOTM2NjE5NzQzMzkyNDU5AAGnNXRpTspgkWsPhK0rHwcSBTmAoJJg1Hhw9DCCRsbkXFywAnMcECH_lsYsAGI_aem_YWdncwDqpKzwqrV-cfeB4N_x6fzM&brid=YWdncwGUvGbTP4059JJLNd3SJJV6" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition-all shadow-md flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                    Belanja Sekarang
                    </a>
                </div>
                </div>
            </div>
            @endforeach
        @else
            @php
            $dummyProducts = [
                ['name' => 'Bibit Buah Naga Kuning Golden Stek', 'price' => 35000, 'unit' => 'batang', 'cat' => 'Bibit', 'tag' => 'Terlaris'],
                ['name' => 'Bibit Buah Naga Merah Super', 'price' => 15000, 'unit' => 'batang', 'cat' => 'Bibit', 'tag' => 'Pilihan'],
                ['name' => 'Buah Naga Kuning Golden Fresh', 'price' => 85000, 'unit' => 'kg', 'cat' => 'Buah Segar', 'tag' => 'Premium'],
                ['name' => 'Pupuk Kompos Organik Khusus Pitaya', 'price' => 25000, 'unit' => 'karung', 'cat' => 'Nutrisi', 'tag' => 'Organik']
            ];
            @endphp
            @foreach($dummyProducts as $dummy)
            <div class="w-[280px] sm:w-[320px] shrink-0 snap-start bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition border border-gray-100 flex flex-col justify-between group">
                <div class="relative">
                <span class="absolute top-4 left-4 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-full z-10 uppercase tracking-widest shadow-sm">{{ $dummy['tag'] }}</span>
                <div class="h-60 bg-gray-50 overflow-hidden">
                    <img src="{{ asset('img/image.png') }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Dummy Item">
                </div>
                </div>
                <div class="p-6">
                <div class="flex text-yellow-400 text-xs mb-2"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <span class="text-[10px] text-green-600 font-extrabold uppercase tracking-wider block mb-1">{{ $dummy['cat'] }}</span>
                <h3 class="text-base font-black text-gray-900 mb-2 group-hover:text-green-600 transition line-clamp-1">{{ $dummy['name'] }}</h3>
                <p class="text-xs text-gray-400 mb-4">Mutu terjamin melalui proses seleksi indukan perkebunan yang matang.</p>
                <div class="flex justify-between items-baseline mb-4 pt-2 border-t border-gray-50">
                    <div>
                    <span class="text-xl font-black text-gray-950">Rp {{ number_format($dummy['price'], 0, ',', '.') }}</span>
                    <span class="text-[10px] text-gray-400 font-bold">/{{ $dummy['unit'] }}</span>
                    </div>
                </div>
                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs uppercase tracking-wider transition">
                    <i class="fas fa-shopping-basket mr-1"></i> Beli Sekarang
                </button>
                </div>
            </div>
            @endforeach
        @endif
        </div>
    </div>
  </section>

  <section class="py-20 bg-emerald-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(#ffffff10_1px,transparent_1px)] [background-size:16px_16px] opacity-30"></div>
    <div class="container mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-12 text-center relative z-10">
      <div>
        <div class="text-amber-500 text-4xl mb-3"><i class="fas fa-history"></i></div>
        <h3 class="text-4xl md:text-6xl font-serif font-black mb-1 tracking-tight">2016</h3>
        <p class="text-emerald-200 text-xs uppercase tracking-widest font-bold">Tahun Didirikan</p>
      </div>
      <div>
        <div class="text-amber-500 text-4xl mb-3"><i class="fas fa-certificate"></i></div>
        <h3 class="text-4xl md:text-6xl font-serif font-black mb-1 tracking-tight">100%</h3>
        <p class="text-emerald-200 text-xs uppercase tracking-widest font-bold">Produk Organik & Alami</p>
      </div>
      <div>
        <div class="text-amber-500 text-4xl mb-3"><i class="fas fa-shield-alt"></i></div>
        <h3 class="text-4xl md:text-6xl font-serif font-black mb-1 tracking-tight">Asli</h3>
        <p class="text-emerald-200 text-xs uppercase tracking-widest font-bold">Jaminan Varietas Lokal</p>
      </div>
      <div>
        <div class="text-amber-500 text-4xl mb-3"><i class="fas fa-handshake"></i></div>
        <h3 class="text-4xl md:text-6xl font-serif font-black mb-1 tracking-tight">Adil</h3>
        <p class="text-emerald-200 text-xs uppercase tracking-widest font-bold">Prinsip Fair Trade</p>
      </div>
    </div>
  </section>

  <section id="process" class="py-24 bg-white">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-3xl mx-auto mb-20">
        <span class="text-orange-500 font-extrabold uppercase tracking-widest text-xs">Alur Kerja Kami</span>
        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mt-2 uppercase">Proses Standardisasi Pembibitan</h2>
        <div class="h-1 w-20 bg-orange-500 mx-auto mt-4"></div>
      </div>

      <div class="grid md:grid-cols-4 gap-8 relative">
        <div class="text-center relative">
          <div class="w-20 h-20 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 border-4 border-white shadow-lg">01</div>
          <h4 class="font-black text-lg text-gray-900 mb-2">Seleksi Indukan</h4>
          <p class="text-xs text-gray-500 leading-relaxed max-w-xs mx-auto">Batang diambil hanya dari pohon indukan buah naga kuning yang sudah terbukti berbuah lebat dan sehat.</p>
        </div>
        <div class="text-center relative">
          <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 border-4 border-white shadow-lg">02</div>
          <h4 class="font-black text-lg text-gray-900 mb-2">Proses Stek Alami</h4>
          <p class="text-xs text-gray-500 leading-relaxed max-w-xs mx-auto">Pemotongan batang dengan presisi sudut khusus untuk merangsang percepatan keluarnya akar kuat.</p>
        </div>
        <div class="text-center relative">
          <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 border-4 border-white shadow-lg">03</div>
          <h4 class="font-black text-lg text-gray-900 mb-2">Fase Karantina</h4>
          <p class="text-xs text-gray-500 leading-relaxed max-w-xs mx-auto">Bibit dirawat intensif di area pembibitan khusus hingga sistem imun tanaman stabil sebelum dipasarkan.</p>
        </div>
        <div class="text-center relative">
          <div class="w-20 h-20 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 border-4 border-white shadow-lg">04</div>
          <h4 class="font-black text-lg text-gray-900 mb-2">Siap Kirim & Tanam</h4>
          <p class="text-xs text-gray-500 leading-relaxed max-w-xs mx-auto">Pengepakan aman anti-stres tanaman, siap dikirim ke lahan perkebunan Anda di seluruh Indonesia.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-24 bg-gray-50 border-t border-gray-100">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-green-600 font-extrabold uppercase tracking-widest text-xs">Testimoni</span>
        <h2 class="text-3xl md:text-4xl font-black text-gray-900 mt-2 uppercase">Kepuasan Pembeli</h2>
      </div>
      
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
          <div>
            <div class="flex text-yellow-400 mb-4 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="text-gray-600 text-sm italic leading-relaxed mb-6">"saya rasa bibit yg saya Terima masih segar, bibit berakar baik dan sudah tumbuh tuntas, bibitnya bagus dan rapi
Cpt sampe walaupun PO dulu"</p>
          </div>
          <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
            <div class="w-12 h-12 bg-gray-200 rounded-xl overflow-hidden"><img src="{{ asset('img/icon-7797704_640.png') }}" class="w-full h-full object-cover" alt="User image"></div>
            <div>
              <h5 class="font-black text-sm text-gray-900">Bapak Rahmat Novianto</h5>
              {{-- <span class="text-[11px] text-gray-400 block font-bold uppercase">Pekebun Mandiri - Majalengka</span> --}}
            </div>
          </div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
          <div>
            <div class="flex text-yellow-400 mb-4 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="text-gray-600 text-sm italic leading-relaxed mb-6">"Kesegaran: segar sekali, Kegunaan: berkebun, Desain: unik, Real pict ada akar dan tunas semoga bisa bertahan dan tumbuh besar hingga berbuah manis"</p>
          </div>
          <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
            <div class="w-12 h-12 bg-gray-200 rounded-xl overflow-hidden"><img src="{{ asset('img/icon-7797704_640.png') }}" class="w-full h-full object-cover" alt="User image"></div>
            <div>
              <h5 class="font-black text-sm text-gray-900">@iennshop</h5>
              {{-- <span class="text-[11px] text-gray-400 block font-bold uppercase">Pelanggan Setia - Bandung</span> --}}
            </div>
          </div>
        </div>
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
          <div>
            <div class="flex text-yellow-400 mb-4 text-xs"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
            <p class="text-gray-600 text-sm italic leading-relaxed mb-6">"Desain: Modern minimalis, Kesegaran: Segar Besar dan Panjang, Kegunaan: Buat di tanam dong masa dibuang"</p>
          </div>
          <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
            <div class="w-12 h-12 bg-gray-200 rounded-xl overflow-hidden"><img src="{{ asset('img/icon-7797704_640.png') }}" class="w-full h-full object-cover" alt="User image"></div>
            <div>
              <h5 class="font-black text-sm text-gray-900">o17ymxl2ns</h5>
              {{-- <span class="text-[11px] text-gray-400 block font-bold uppercase">Mahasiswa Agribisnis</span> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="stories" class="py-24 bg-white border-t border-gray-100">
    <div class="container mx-auto px-6">
      <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="text-orange-500 font-extrabold uppercase tracking-widest text-xs">Kiwari Stories</span>
        <h2 class="text-3xl md:text-4xl font-black text-gray-900 uppercase mt-2">Aktivitas Kebun Kami</h2>
        <div class="h-1 w-20 bg-green-500 mx-auto mt-4"></div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-gray-900 rounded-3xl overflow-hidden shadow-lg group relative aspect-[9/16] border border-gray-800">
          <video class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-500" autoplay muted loop playsinline>
            <source src="{{ asset('video/Vidio1.mp4') }}" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
            <span class="text-orange-400 text-[10px] font-black uppercase tracking-widest mb-1">Live Update</span>
            <h4 class="text-white font-black text-base leading-tight">Perbedaan Bibit Buah Naga</h4>
            <p class="text-gray-300 text-xs mt-1">Perbedaan bibit buah naga stek dan bibit buah naga premium kiwari farm sudah berakar dan tunas panjang</p>
          </div>
        </div>

        <div class="bg-gray-900 rounded-3xl overflow-hidden shadow-lg group relative aspect-[9/16] border border-gray-800">
          <video class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-500" autoplay muted loop playsinline>
            <source src="{{ asset('video/Vidio2.mp4') }}" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
            <span class="text-green-400 text-[10px] font-black uppercase tracking-widest mb-1">Logistik</span>
            <h4 class="text-white font-black text-base leading-tight">Teknik Stek Batang Unggul</h4>
            <p class="text-gray-300 text-xs mt-1">Standardisasi perlindungan pengiriman ke luar pulau Jawa via kargo udara.</p>
          </div>
        </div>

        <div class="bg-gray-900 rounded-3xl overflow-hidden shadow-lg group relative aspect-[9/16] border border-gray-800">
          <video class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-500" autoplay muted loop playsinline>
            <source src="{{ asset('video/Vidio3.mp4') }}" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
            <span class="text-orange-400 text-[10px] font-black uppercase tracking-widest mb-1">Agro-tourism</span>
            <h4 class="text-white font-black text-base leading-tight">3 bibit untuk pemula</h4>
            <p class="text-gray-300 text-xs mt-1">Penjelasan tentang tiga jenis bibit buah naga yang cocok untuk pemula.</p>
          </div>
        </div>

        <div class="bg-gray-900 rounded-3xl overflow-hidden shadow-lg group relative aspect-[9/16] border border-gray-800">
          <video class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition duration-500" autoplay muted loop playsinline>
            <source src="{{ asset('video/Vidio4.mp4') }}" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
            <span class="text-green-400 text-[10px] font-black uppercase tracking-widest mb-1">Kualitas</span>
            <h4 class="text-white font-black text-base leading-tight">Budidaya Buah Naga</h4>
            <p class="text-gray-300 text-xs mt-1">Berbagi ilmu adalah bagian dari perjalanan, senang sekali bisa hadir dan berbagi pengalaman di banyuwangi</p>
          </div>
        </div>
      </div>
    </div>
  </section>

   <footer class="bg-[#141414] text-gray-400 pt-20 pb-8">
    <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
      <div>
        <img src="{{ asset('img/Logo.png') }}" alt="Logo Footer" class="h-14 mb-6 brightness-200 contrast-200">
        <p class="text-xs leading-relaxed mb-6 text-justify">
          Kiwari Farm Indonesia berkomitmen penuh menyediakan produk agrikultur premium sehat konsumsi, sembari merawat kelestarian ekosistem tanah nusantara demi generasi masa depan.
        </p>
        <div class="flex space-x-3">
          <a href="#" class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center hover:bg-green-600 hover:text-white transition"><i class="fab fa-twitter text-xs"></i></a>
          <a href="#" class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center hover:bg-green-600 hover:text-white transition"><i class="fab fa-facebook-f text-xs"></i></a>
          <a href="#" class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center hover:bg-green-600 hover:text-white transition"><i class="fab fa-instagram text-xs"></i></a>
          <a href="#" class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center hover:bg-green-600 hover:text-white transition"><i class="fab fa-linkedin-in text-xs"></i></a>
        </div>
      </div>
      
      <div>
        <h4 class="text-white font-black mb-6 uppercase tracking-widest text-xs border-l-2 border-green-500 pl-3">Navigasi Bisnis</h4>
        <ul class="space-y-3 text-xs">
          <li><a href="#about" class="hover:text-green-500 transition block">Profil Kiwari Farm</a></li>
          <li><a href="#products" class="hover:text-green-500 transition block">Katalog Sayur & Buah</a></li>
          <li><a href="#" class="hover:text-green-500 transition block">Skema Kemitraan Tani</a></li>
          <li><a href="#" class="hover:text-green-500 transition block">Kebijakan Privasi & Hukum</a></li>
          <li><a href="#" class="hover:text-green-500 transition block">Syarat Ketentuan Layanan</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-white font-black mb-6 uppercase tracking-widest text-xs border-l-2 border-green-500 pl-3">Jurnal Terbaru</h4>
        <div class="space-y-4">
          <div>
            <a href="#" class="text-white text-xs block hover:text-green-500 transition font-bold line-clamp-2">Membawa Kembali Pangan ke Area Perkotaan</a>
            <span class="text-[10px] text-green-500 font-bold block mt-1">July 5, 2026</span>
          </div>
          <div>
            <a href="#" class="text-white text-xs block hover:text-green-500 transition font-bold line-clamp-2">Masa Depan Pertanian, Solusi Irigasi Pintar IoT</a>
            <span class="text-[10px] text-green-500 font-bold block mt-1">July 5, 2026</span>
          </div>
        </div>
      </div>

      <div>
        <h4 class="text-white font-black mb-6 uppercase tracking-widest text-xs border-l-2 border-green-500 pl-3">Kontak & HQ</h4>
        <ul class="space-y-4 text-xs">
          <li class="flex items-start gap-3">
            <i class="fas fa-phone text-green-500 mt-0.5"></i> 
            <span>+62 812-3456-7890<br><span class="text-gray-500 text-[11px]">(WhatsApp Only)</span></span>
          </li>
          <li class="flex items-center gap-3">
            <i class="fas fa-envelope text-green-500"></i> 
            <span>support@kiwarifarm.com</span>
          </li>
          <li class="flex items-start gap-3">
            <i class="fas fa-map-marker-alt text-green-500 mt-0.5"></i> 
            <span>Jl. Raya Eco Farming No. 88, Kec. Jalancagak, Subang, Jawa Barat 41281</span>
          </li>
        </ul>
      </div>
    </div>
    
    <div class="container mx-auto px-6 mt-16 pt-8 border-t border-gray-900 text-center text-[11px] font-medium tracking-wider text-gray-600">
      © All Copyright 2026 by Kiwari Farm Indonesia. Dikembangkan Bersama Komunitas Petani Muda Subang.
    </div>
  </footer>

</body>
</html>