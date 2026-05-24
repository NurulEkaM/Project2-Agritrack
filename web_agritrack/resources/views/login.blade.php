<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kiwari Farm</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap');
        body { font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center m-0">

    <div class="fixed inset-0 flex -z-10">
        <div class="w-1/2 bg-[#ebf1ee]"></div> 
        <div class="w-1/2 bg-[#4ca744]"></div> 
    </div>

    <div class="container max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-center px-4">
        
        <div class="bg-white w-full max-w-md p-12 md:p-16 shadow-lg z-20 md:-mr-20">
            <h2 class="text-3xl font-extrabold text-black mb-12 tracking-tight">LOGIN HERE</h2>
            
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                @if($errors->any())
                    <div class="bg-red-50 text-red-500 text-[10px] p-3 rounded mb-4 border border-red-100">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" required
                        class="w-full px-4 py-3 bg-[#f7f7f7] border-none focus:ring-1 focus:ring-gray-300 outline-none text-sm placeholder-gray-400">
                </div>
                
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-3 bg-[#f7f7f7] border-none focus:ring-1 focus:ring-gray-300 outline-none text-sm placeholder-gray-400">
                </div>
                
                <div class="pt-8">
                    <button type="submit" 
                        class="bg-[#fbc565] hover:bg-[#f9b233] text-black font-extrabold py-3 px-12 rounded-sm transition duration-300 text-xs tracking-widest uppercase">
                        LOGIN
                    </button>
                </div>
            </form>
        </div>

        <div class="w-full max-w-md text-white py-12 md:pl-32 mt-10 md:mt-0">
            <h2 class="text-3xl font-bold mb-4">Contact Information</h2>
            <div class="flex gap-1 mb-8">
                <div class="w-6 h-1 bg-[#fbc565]"></div>
                <div class="w-2 h-1 bg-[#fbc565]"></div>
            </div>

            <p class="text-xs leading-relaxed mb-10 text-gray-100 opacity-90 uppercase tracking-wider">
                Plan upon yet way get cold spot its week. Almost do am or limits hearts. 
                Resolve parties but why she showing.
            </p>

            <div class="space-y-8">
                <div>
                    <h4 class="font-bold text-sm mb-1 uppercase">Hotline</h4>
                    <p class="text-xs text-gray-100">+4733378901</p>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-1 uppercase">Our Location</h4>
                    <p class="text-xs text-gray-100 italic leading-relaxed">
                        55 Main Street, The Grand Avenue 2nd Block,<br>New York City
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-sm mb-1 uppercase">Official Email</h4>
                    <p class="text-xs text-gray-100">info@agrul.com</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>