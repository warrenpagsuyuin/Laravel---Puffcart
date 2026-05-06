<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age Verification — Puffcart</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @endif
</head>
<body class="bg-[#04050d] font-mono min-h-screen flex items-center justify-center"
      style="background-image:linear-gradient(#00ffe708 1px,transparent 1px),linear-gradient(90deg,#00ffe708 1px,transparent 1px);background-size:32px 32px">

    <div class="fixed inset-0 pointer-events-none"
         style="background:repeating-linear-gradient(0deg,transparent,transparent 2px,rgba(0,255,231,0.012) 2px,rgba(0,255,231,0.012) 4px)"></div>

    <div class="relative bg-[#0a0e1c] border border-[#007a6e] rounded-sm p-8 max-w-sm w-full mx-4 text-center">
        {{-- Cyber corners --}}
        <div class="absolute top-0 left-0 w-3 h-3 border-t-2 border-l-2 border-[#00ffe7]"></div>
        <div class="absolute top-0 right-0 w-3 h-3 border-t-2 border-r-2 border-[#00ffe7]"></div>
        <div class="absolute bottom-0 left-0 w-3 h-3 border-b-2 border-l-2 border-[#00ffe7]"></div>
        <div class="absolute bottom-0 right-0 w-3 h-3 border-b-2 border-r-2 border-[#00ffe7]"></div>

        <div class="w-14 h-14 rounded-full bg-[#001f1c] border border-[#007a6e] flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="#00ffe7" stroke-width="1.5">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <path d="M9 12l2 2 4-4"/>
            </svg>
        </div>

        <div class="font-['Orbitron'] text-lg text-[#00ffe7] tracking-widest mb-2" style="text-shadow:0 0 12px #00ffe7">
            AGE VERIFICATION
        </div>
        <p class="text-[11px] text-[#5a8fa8] leading-relaxed mb-1">
            Puffcart sells tobacco and nicotine products.
        </p>
        <p class="text-[11px] text-[#5a8fa8] leading-relaxed mb-5">
            You must be <span class="text-[#00ffe7] font-bold">18 years or older</span> to enter this site.
        </p>

        <form method="POST" action="{{ route('age.verify.post') }}">
            @csrf
            <input type="hidden" name="confirmed" value="1">
            <button type="submit"
                    class="w-full mb-3 font-['Orbitron'] text-[10px] py-3 bg-[#00ffe7] text-[#04050d] font-bold tracking-widest"
                    style="clip-path:polygon(8px 0,100% 0,100% calc(100% - 8px),calc(100% - 8px) 100%,0 100%,0 8px)">
                I AM 18 OR OLDER →
            </button>
        </form>

        <a href="https://www.google.com"
           class="block w-full font-['Orbitron'] text-[10px] py-2.5 text-[#5a8fa8] border border-[#00ffe718] hover:bg-[#00ffe708] tracking-widest">
            I AM UNDER 18
        </a>

        <p class="text-[9px] text-[#1e3d52] mt-4">
            By entering, you confirm you are of legal vaping age and agree to our Terms of Service.
        </p>
    </div>
</body>
</html>
