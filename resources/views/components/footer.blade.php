<footer class="relative z-10 border-t border-blue-900/40 bg-[#02050f]/90 backdrop-blur-md pt-16 pb-12 text-slate-400">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-blue-950/60">
            
            <!-- Brand Column -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-800 p-0.5 shadow-md shadow-blue-500/20">
                        <div class="w-full h-full bg-[#030712] rounded-[10px] flex items-center justify-center">
                            <span class="font-cinzel text-lg font-bold text-blue-400">A</span>
                        </div>
                    </div>
                    <div>
                        <span class="font-cinzel text-xl font-bold tracking-wider text-white">AOCC</span>
                        <p class="text-[11px] text-blue-400 font-medium">Alfa Omega Community Church</p>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Sebuah keluarga rohani di mana setiap jiwa dipulihkan, diperlengkapi, dan diutus untuk menjadi garam dan terang bagi kemuliaan Kristus.
                </p>
                <div class="pt-2">
                    <button id="btn-replay-intro" 
                            type="button" 
                            class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-1.5 rounded-full bg-blue-950/60 hover:bg-blue-900/60 text-blue-300 border border-blue-500/30 transition-all hover:border-blue-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Putar Ulang Intro Sinematik</span>
                    </button>
                </div>
            </div>

            <!-- Quick Navigation Links -->
            <div class="space-y-4">
                <h4 class="text-xs uppercase tracking-widest font-bold text-blue-400">Navigasi Utama</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('home') }}#hero" class="hover:text-blue-300 transition-colors">Beranda (Home)</a></li>
                    <li><a href="{{ route('home') }}#tentang" class="hover:text-blue-300 transition-colors">Tentang Kami & BETHEL</a></li>
                    <li><a href="{{ route('home') }}#ibadah" class="hover:text-blue-300 transition-colors">Jadwal 6 Ibadah</a></li>
                    <li><a href="{{ route('home') }}#beranda" class="hover:text-blue-300 transition-colors">Komunitas Sel (Beranda)</a></li>
                    <li><a href="{{ route('sermons.index') }}" class="hover:text-blue-300 transition-colors">Arsip Khotbah (Sermon)</a></li>
                    <li><a href="{{ route('events.index') }}" class="hover:text-blue-300 transition-colors">Agenda Acara & Kegiatan</a></li>
                </ul>
            </div>

            <!-- Contact & Service Info -->
            <div class="space-y-4">
                <h4 class="text-xs uppercase tracking-widest font-bold text-blue-400">Hubungi Pelayanan</h4>
                <div class="space-y-3 text-sm">
                    <p class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-blue-400 mt-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Gedung Grha Alfa Omega, Jl. Boulevard Barat Kav. 88, Kelapa Gading, Jakarta Utara</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span>+62 21 4587 9000</span>
                    </p>
                    <p class="flex items-center gap-2.5 text-blue-300">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.971.53 1.83.812 2.796.812 3.179 0 5.767-2.587 5.767-5.766.001-3.181-2.585-5.799-5.767-5.799z"/>
                        </svg>
                        <a href="https://wa.me/6281288997766?text=Shalom%20Pakdhe%20Ardi" target="_blank" class="hover:underline font-medium">
                            Pakdhe Ardi: +62 812-8899-7766
                        </a>
                    </p>
                </div>
            </div>

            <!-- Worship Hours Badge -->
            <div class="space-y-4">
                <h4 class="text-xs uppercase tracking-widest font-bold text-blue-400">Ibadah Utama</h4>
                <div class="glass-panel p-4 rounded-2xl border-blue-500/20 space-y-2">
                    <span class="inline-block text-[11px] font-semibold uppercase px-2 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-400/30">
                        Ibadah Raya Minggu
                    </span>
                    <p class="text-white font-semibold text-base">Pkl 09:00 - 11:00 WIB</p>
                    <p class="text-xs text-slate-400">Main Sanctuary Lt. 3 • Onsite & Streaming</p>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <a href="https://youtube.com/@aoccchurch" target="_blank" class="w-9 h-9 rounded-full bg-blue-950 flex items-center justify-center text-slate-300 hover:text-white hover:bg-blue-800 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="https://instagram.com/aoccchurch" target="_blank" class="w-9 h-9 rounded-full bg-blue-950 flex items-center justify-center text-slate-300 hover:text-white hover:bg-blue-800 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>

        </div>

        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <p>&copy; {{ date('Y') }} Alfa Omega Community Church (AOCC). Hak Cipta Dilindungi.</p>
            <p class="text-blue-400/80 font-serif italic text-[13px]">"Pada mulanya Allah menciptakan langit dan bumi." — Kejadian 1:1</p>
        </div>
    </div>
</footer>
