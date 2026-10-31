@php
    $isHome = request()->routeIs('home');
@endphp

<header id="main-header" class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 py-4 px-4 sm:px-8">
    <div id="navbar-container" class="max-w-7xl mx-auto flex items-center justify-between transition-all duration-500 rounded-full px-4 sm:px-6 py-2.5">
        
        <!-- Logo Wrapper (animates opacity & width on scroll as per PRD §6.2) -->
        <div id="nav-logo-wrapper" class="flex items-center gap-3 transition-all duration-500 overflow-hidden pr-2">
            <a href="{{ route('home') }}#hero" class="flex items-center gap-3 group focus:outline-none" aria-label="AOCC Home">
                <div class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-900 p-0.5 shadow-lg shadow-blue-600/30 group-hover:shadow-blue-500/50 transition-all duration-300">
                    <div class="w-full h-full bg-[#030712] rounded-[10px] flex items-center justify-center">
                        <!-- Custom Stylized Alpha Omega Logo (SVG) -->
                        <svg class="w-6 h-6 text-blue-400 group-hover:text-blue-300 transition-colors" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="8" stroke-linecap="round" stroke-linejoin="round">
                            <!-- Alpha shape -->
                            <path d="M 25,75 C 20,40 45,22 55,48 C 65,70 85,75 85,75" />
                            <path d="M 20,62 Q 50,45 80,62" />
                            <!-- Cross accent inside -->
                            <line x1="50" y1="20" x2="50" y2="40" stroke-width="6" class="text-cyan-300" />
                            <line x1="42" y1="28" x2="58" y2="28" stroke-width="6" class="text-cyan-300" />
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col whitespace-nowrap">
                    <span class="font-cinzel text-base sm:text-lg font-bold tracking-wider text-white group-hover:text-blue-300 transition-colors">
                        AOCC
                    </span>
                    <span class="text-[9px] uppercase tracking-widest text-blue-400/90 font-medium hidden sm:inline">
                        Alfa Omega
                    </span>
                </div>
            </a>
        </div>

        <!-- Desktop Navigation Items (PRD §6.2: Logo | Tentang | Ibadah | Beranda | Sermon | Acara) -->
        <nav id="nav-desktop-menu" class="hidden md:flex items-center relative p-1 rounded-full bg-slate-950/40 border border-blue-500/15 backdrop-blur-md">
            <!-- Sliding Active Pill Indicator -->
            <div id="nav-active-pill" class="absolute top-1 bottom-1 rounded-full bg-gradient-to-r from-blue-600/40 to-cyan-500/30 border border-blue-400/40 shadow-[0_0_15px_rgba(59,130,246,0.3)] transition-all duration-300 pointer-events-none opacity-0"></div>

            <ul class="flex items-center gap-1 relative z-10 text-xs sm:text-sm font-medium text-slate-300">
                <li>
                    <a href="{{ $isHome ? '#tentang' : route('home').'#tentang' }}" 
                       data-nav-target="tentang"
                       class="nav-link block px-3.5 py-1.5 rounded-full transition-colors hover:text-white hover:shadow-sm">
                        Tentang
                    </a>
                </li>
                <li>
                    <a href="{{ $isHome ? '#ibadah' : route('home').'#ibadah' }}" 
                       data-nav-target="ibadah"
                       class="nav-link block px-3.5 py-1.5 rounded-full transition-colors hover:text-white">
                        Ibadah
                    </a>
                </li>
                <li>
                    <a href="{{ $isHome ? '#beranda' : route('home').'#beranda' }}" 
                       data-nav-target="beranda"
                       class="nav-link block px-3.5 py-1.5 rounded-full transition-colors hover:text-white">
                        Beranda
                    </a>
                </li>
                <li>
                    <a href="{{ $isHome ? '#sermon' : route('home').'#sermon' }}" 
                       data-nav-target="sermon"
                       class="nav-link block px-3.5 py-1.5 rounded-full transition-colors hover:text-white">
                        Sermon
                    </a>
                </li>
                <li>
                    <a href="{{ $isHome ? '#acara' : route('home').'#acara' }}" 
                       data-nav-target="acara"
                       class="nav-link block px-3.5 py-1.5 rounded-full transition-colors hover:text-white">
                        Acara
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Right Quick Actions: WhatsApp & Mobile Toggle -->
        <div class="flex items-center gap-3">
            <a href="https://wa.me/6281288997766?text=Shalom%20Pakdhe%20Ardi,%20saya%20ingin%20bertanya%20seputar%20AOCC" 
               target="_blank" 
               rel="noopener noreferrer"
               class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.971.53 1.83.812 2.796.812 3.179 0 5.767-2.587 5.767-5.766.001-3.181-2.585-5.799-5.767-5.799zm3.364 8.204c-.14.394-.817.75-1.127.798-.31.05-.694.075-1.116-.062-.266-.086-.607-.202-1.029-.384-1.797-.777-2.966-2.6-3.056-2.72-.09-.12-.733-.974-.733-1.859 0-.885.465-1.32.63-1.501.166-.181.364-.227.485-.227.121 0 .243.001.349.006.111.006.262-.042.409.313.152.364.517 1.261.562 1.353.045.091.076.197.015.319-.061.121-.091.197-.182.303-.091.106-.192.237-.274.318-.091.091-.186.19-.08.372.106.182.471.777 1.011 1.258.696.62 1.282.812 1.464.903.182.091.288.076.394-.045.106-.122.455-.53.576-.712.121-.182.242-.152.409-.091.167.061 1.061.5 1.243.591.182.091.303.136.349.212.045.076.045.44-.095.834z"/>
                </svg>
                <span>Pakdhe Ardi (WA)</span>
            </a>

            <!-- Mobile Hamburger Button -->
            <button id="mobile-menu-btn" 
                    type="button" 
                    class="md:hidden p-2 rounded-xl bg-slate-900/80 border border-blue-500/20 text-slate-300 hover:text-white focus:outline-none"
                    aria-label="Toggle Navigation Menu">
                <svg id="hamburger-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
                <svg id="close-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div id="mobile-menu-drawer" class="md:hidden hidden pt-3 px-2">
        <div class="glass-panel-glow rounded-2xl p-5 space-y-3">
            <a href="{{ $isHome ? '#hero' : route('home').'#hero' }}" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-slate-200 hover:bg-blue-600/20 hover:text-blue-300 font-medium transition-colors">
                Beranda Utama
            </a>
            <a href="{{ $isHome ? '#tentang' : route('home').'#tentang' }}" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-slate-200 hover:bg-blue-600/20 hover:text-blue-300 font-medium transition-colors">
                Tentang AOCC
            </a>
            <a href="{{ $isHome ? '#ibadah' : route('home').'#ibadah' }}" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-slate-200 hover:bg-blue-600/20 hover:text-blue-300 font-medium transition-colors">
                Jadwal Ibadah (6 Jenis)
            </a>
            <a href="{{ $isHome ? '#beranda' : route('home').'#beranda' }}" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-slate-200 hover:bg-blue-600/20 hover:text-blue-300 font-medium transition-colors">
                Beranda (Komunitas Sel)
            </a>
            <a href="{{ $isHome ? '#sermon' : route('home').'#sermon' }}" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-slate-200 hover:bg-blue-600/20 hover:text-blue-300 font-medium transition-colors">
                Sermon & Khotbah
            </a>
            <a href="{{ $isHome ? '#acara' : route('home').'#acara' }}" class="mobile-nav-link block px-4 py-2.5 rounded-xl text-slate-200 hover:bg-blue-600/20 hover:text-blue-300 font-medium transition-colors">
                Acara & Kegiatan
            </a>
            <div class="pt-3 border-t border-blue-500/20">
                <a href="https://wa.me/6281288997766?text=Shalom%20Pakdhe%20Ardi" 
                   target="_blank" 
                   class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-semibold text-sm shadow-md">
                    <span>Hubungi Pakdhe Ardi (WhatsApp)</span>
                </a>
            </div>
        </div>
    </div>
</header>
