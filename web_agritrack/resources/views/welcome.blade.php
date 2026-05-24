<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiwari Farm Indonesia - Agriculture Eco Farming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('img/image.png');
            background-size: cover;
            background-position: center;
        }
        .brush-border {
            mask-image: url('https://www.transparenttextures.com/patterns/black-linen.png'); /* Variasi tekstur pinggiran */
        }
    </style>
</head>
<body class="bg-gray-50">

    <nav class="bg-white/90 backdrop-blur-md sticky top-0 z-50 py-4 shadow-sm">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-20 ">
            <ul class="hidden md:flex space-x-8 text-sm font-semibold uppercase text-gray-700">
                <li class="text-green-600"><a href="/login">Login</a></li>
            </ul>
        </div>
    </nav>

    <section class="hero-section h-[800px] flex items-center relative text-white">
        
        <div class="container mx-auto px-6 text-center md:text-left">
            <h4 class="uppercase tracking-widest text-sm mb-4">Welcome to Kiwari Farm</h4>
            <h1 class="text-6xl md:text-8xl font-serif font-bold mb-6 italic leading-tight">
                Agriculture <br> Eco Farming
            </h1>
            <p class="max-w-lg mb-8 text-gray-200">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </p>
            <a href="#" class="bg-green-500 hover:bg-green-600 px-8 py-3 rounded-md font-bold transition">Visit Store</a>
        </div>

        <div class="absolute -bottom-20 left-0 right-0 hidden md:flex justify-center gap-6 container mx-auto px-6">
            @foreach(['Daily Fresh Produce', 'Fresh Vegetables', 'Daily Fresh Products'] as $title)
            <div class="bg-white text-gray-800 p-6 rounded-lg shadow-xl w-1/3 text-center border-b-4 border-green-500">
                <h3 class="font-bold text-green-600 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 mb-4 text-justify">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
                <img src="{{ asset('img/image.png') }}" class="w-12 h-12 rounded-full mx-auto border-2 border-green-200" alt="">
            </div>
            @endforeach
        </div>
    </section>

    <section class="py-40 bg-white">
        <div class="container mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="w-[450px] h-[450px] rounded-full overflow-hidden border-8 border-gray-100 shadow-2xl">
                    <img src="{{ asset('img/image.png') }}" class="w-full h-full object-cover">
                </div>
                <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full overflow-hidden border-8 border-white shadow-xl translate-y-10">
                    <img src="{{ asset('img/image.png') }}" class="w-full h-full object-cover">
                </div>
            </div>
            <div>
                <h4 class="text-orange-400 font-bold mb-2">About us</h4>
                <h2 class="text-4xl font-bold text-gray-800 mb-4 uppercase">Kiwari Farm Indonesia</h2>
                <p class="text-green-600 font-semibold mb-4 italic">Agries is the largest global organic farm.</p>
                <p class="text-gray-500 mb-8 leading-relaxed">
                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
                </p>
                
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-carrot text-orange-400 text-2xl"></i>
                        <span class="font-bold text-gray-700">Growing fruits vegetables</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-seedling text-orange-400 text-2xl"></i>
                        <span class="font-bold text-gray-700">Tips for ripening your fruits</span>
                    </div>
                </div>

                <ul class="space-y-3 mb-8">
                    <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Lorem Ipsum is not simply random text.</li>
                    <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Making this the first true generator on the Internet.</li>
                </ul>
                <a href="#" class="bg-green-500 text-white px-8 py-3 rounded-md font-bold hover:bg-green-600">Discover More</a>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6 text-center">
            <h4 class="text-orange-400 font-bold mb-2 uppercase italic text-sm">News</h4>
            <h2 class="text-4xl font-bold text-gray-800 mb-12">Konten Kami</h2>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $blogs = [
                        ['img' => 'image.png', 'title' => 'Bringing Food Production Back To Cities'],
                        ['img' => 'image.png', 'title' => 'The Future of Farming, Smart Irrigation Solutions'],
                        ['img' => 'image.png', 'title' => 'Agronomy and relation to Other Sciences'],
                    ];
                @endphp

                @foreach($blogs as $blog)
                <div class="bg-white rounded-xl overflow-hidden shadow-lg group">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('img/' . $blog['img']) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-center gap-4 text-xs text-orange-500 font-bold mb-4 uppercase">
                            <span><i class="fas fa-user"></i> Fresh Health</span>
                            <span><i class="fas fa-comments"></i> 2 Comments</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 hover:text-green-600 transition cursor-pointer">
                            {{ $blog['title'] }}
                        </h3>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer class="bg-[#1a1a1a] text-gray-400 py-16">
        <div class="container mx-auto px-6 grid md:grid-cols-4 gap-12">
            <div>
                <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-12 mb-6 brightness-200 contrast-200">
                <p class="text-sm leading-relaxed mb-6">
                    There are many variations of passages of Lorem Ipsum available, but the majority have suffered.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="hover:text-white"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-white"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Explore</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-green-500">About</a></li>
                    <li><a href="#" class="hover:text-green-500">Services</a></li>
                    <li><a href="#" class="hover:text-green-500">Our Projects</a></li>
                    <li><a href="#" class="hover:text-green-500">Meet the Farmers</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">News</h4>
                <div class="space-y-4">
                    <div>
                        <a href="#" class="text-white text-sm block hover:text-green-500">Bringing Food Production Back To Cities</a>
                        <span class="text-xs text-green-500">July 5, 2026</span>
                    </div>
                    <div>
                        <a href="#" class="text-white text-sm block hover:text-green-500">The Future of Farming, Smart Irrigation Solutions</a>
                        <span class="text-xs text-green-500">July 5, 2026</span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider">Contact</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-center gap-3"><i class="fas fa-phone text-green-500"></i> +62 812-XXXX-XXXX</li>
                    <li class="flex items-center gap-3"><i class="fas fa-envelope text-green-500"></i> info@kiwarifarm.com</li>
                    <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-green-500"></i> Subang, Jawa Barat, Indonesia</li>
                </ul>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-16 pt-8 border-t border-gray-800 text-center text-xs">
            © All Copyright 2026 by Kiwari Farm Indonesia
        </div>
    </footer>

</body>
</html>