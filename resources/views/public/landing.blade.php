@extends('layouts.app')

@section('title', 'Alfa Omega Community Church (AOCC) — Welcome Home')

@section('content')

<!-- ========================================================================= -->
<!-- 1. CINEMATIC INTRO OVERLAY (PRD §6.1 Timeline & Specifications)          -->
<!-- ========================================================================= -->
<div id="cinematic-intro" 
     class="fixed inset-0 z-[100] bg-[#02040a] flex flex-col items-center justify-center p-6 select-none overflow-hidden transition-opacity duration-1000">
    
    <!-- Skip Intro Button (Always visible as required by PRD §6.1) -->
    <button id="btn-skip-intro" 
            type="button" 
            class="absolute top-6 right-6 z-50 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider uppercase text-blue-300/80 bg-blue-950/60 border border-blue-500/30 hover:bg-blue-900/60 hover:text-white transition-all backdrop-blur-md">
        Lewati / Skip &rarr;
    </button>

    <!-- Stage 1 & 2: AOCC Title & Fullname Typography -->
    <div id="intro-titles" class="text-center space-y-4 opacity-0 scale-95 transition-all duration-700">
        <h1 id="intro-aocc-text" class="font-cinzel text-6xl sm:text-8xl md:text-9xl font-black tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-white to-blue-400 drop-shadow-[0_0_35px_rgba(59,130,246,0.6)]">
            AOCC
        </h1>
        <p id="intro-sub-text" class="text-sm sm:text-base md:text-lg tracking-[0.3em] uppercase text-blue-300 font-medium font-sans">
            Alfa Omega Community Church
        </p>
    </div>

    <!-- Stage 3: Logo Vector Stroke Reveal (PRD §6.1 Step-by-step vector SVG) -->
    <div id="intro-logo-container" class="opacity-0 hidden flex flex-col items-center space-y-6">
        <div class="relative w-28 h-28 sm:w-36 sm:h-36">
            <svg id="intro-logo-svg" class="w-full h-full text-blue-400 drop-shadow-[0_0_25px_rgba(59,130,246,0.8)]" viewBox="0 0 100 100" fill="none">
                <!-- Alpha Stroke Path -->
                <path id="svg-alpha-path" 
                      d="M 25,80 C 18,35 45,18 55,48 C 65,72 85,80 88,78" 
                      stroke="url(#blue-grad)" 
                      stroke-width="6" 
                      stroke-linecap="round" 
                      stroke-linejoin="round"
                      stroke-dasharray="300"
                      stroke-dashoffset="300" />
                <path id="svg-alpha-bar" 
                      d="M 18,65 Q 50,48 82,65" 
                      stroke="#67e8f9" 
                      stroke-width="5" 
                      stroke-linecap="round"
                      stroke-dasharray="100"
                      stroke-dashoffset="100" />
                <!-- Cross Accent -->
                <line id="svg-cross-v" x1="50" y1="16" x2="50" y2="40" stroke="#93c5fd" stroke-width="4.5" stroke-linecap="round" stroke-dasharray="50" stroke-dashoffset="50" />
                <line id="svg-cross-h" x1="40" y1="26" x2="60" y2="26" stroke="#93c5fd" stroke-width="4.5" stroke-linecap="round" stroke-dasharray="50" stroke-dashoffset="50" />

                <defs>
                    <linearGradient id="blue-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#38bdf8" />
                        <stop offset="50%" stop-color="#3b82f6" />
                        <stop offset="100%" stop-color="#1d4ed8" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Stage 4: Handwriting / Lettering "Welcome Home" -->
    <div id="intro-welcome-container" class="opacity-0 hidden text-center space-y-3 mt-4">
        <h2 id="intro-welcome-text" class="font-script text-5xl sm:text-7xl md:text-8xl text-transparent bg-clip-text bg-gradient-to-r from-blue-200 via-white to-cyan-300 drop-shadow-[0_0_30px_rgba(59,130,246,0.7)] py-2">
            Welcome Home
        </h2>
    </div>

    <!-- Stage 5: Daily Verse on Intro -->
    <div id="intro-verse-container" class="opacity-0 hidden max-w-lg text-center mt-6 p-4 rounded-2xl glass-panel border border-blue-500/20">
        <p class="font-serif italic text-sm sm:text-base text-slate-200 leading-relaxed">
            "{{ $dailyVerse->text }}"
        </p>
        <span class="block mt-2 font-bold text-xs uppercase tracking-widest text-blue-400">
            {{ $dailyVerse->reference }} ({{ $dailyVerse->translation }})
        </span>
    </div>

</div>


<!-- ========================================================================= -->
<!-- 2. HERO SECTION (PRD §6.3 Fullscreen: Welcome Home + Ayat Harian)          -->
<!-- ========================================================================= -->
<section id="hero" class="relative min-h-screen flex items-center justify-center pt-24 pb-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    
    <!-- Hero Ambient Visual Background -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-gradient-to-b from-blue-600/15 via-indigo-900/10 to-transparent rounded-full blur-[140px]"></div>
        <div class="absolute -bottom-20 left-10 w-96 h-96 bg-blue-500/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto text-center space-y-8">
        
        <!-- Church Name Small Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-950/60 border border-blue-500/30 text-blue-300 text-xs sm:text-sm font-medium tracking-wider backdrop-blur-md shadow-lg shadow-blue-900/20 animate-fade-in">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            <span>ALFA OMEGA COMMUNITY CHURCH</span>
        </div>

        <!-- Majestic Hero Title -->
        <div class="space-y-2">
            <h1 class="font-script text-6xl sm:text-8xl md:text-9xl text-white drop-shadow-[0_0_40px_rgba(59,130,246,0.45)] leading-tight">
                Welcome Home
            </h1>
            <p class="text-base sm:text-xl md:text-2xl text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">
                Rumah rohani bagi setiap jiwa untuk <span class="text-white font-medium">dipulihkan</span>, <span class="text-white font-medium">diperlengkapi</span>, dan <span class="text-blue-300 font-medium">berbuah bagi Kerajaan Allah</span>.
            </p>
        </div>

        <!-- Daily Verse Card (PRD §6.1 Kejadian 1:1 fallback) -->
        <div class="max-w-2xl mx-auto glass-panel-glow p-6 sm:p-8 rounded-3xl border border-blue-500/25 relative group hover:border-blue-400/40 transition-all">
            <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-0.5 rounded-full bg-gradient-to-r from-blue-600 to-cyan-600 text-[10px] font-bold uppercase tracking-widest text-white shadow-md">
                Ayat Hari Ini
            </div>
            <p class="font-serif italic text-base sm:text-lg text-slate-100 leading-relaxed pt-2">
                "{{ $dailyVerse->text }}"
            </p>
            <div class="mt-4 pt-3 border-t border-blue-900/40 flex items-center justify-between text-xs text-blue-300">
                <span class="font-bold tracking-wider uppercase">{{ $dailyVerse->reference }}</span>
                <span class="text-slate-400 text-[11px]">Terjemahan Baru ({{ $dailyVerse->translation }})</span>
            </div>
        </div>

        <!-- Call-to-Action Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            <a href="#ibadah" class="px-7 py-3 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-105 active:scale-95 transition-all">
                Jadwal Ibadah Minggu
            </a>
            <a href="#tentang" class="px-7 py-3 rounded-full text-sm font-semibold glass-panel text-slate-200 hover:text-white hover:bg-blue-950/40 border border-blue-500/30 hover:border-blue-400 transition-all">
                Tentang AOCC & Filosofi
            </a>
            <a href="{{ $pakdheArdi->whatsapp_url }}" target="_blank" class="px-6 py-3 rounded-full text-sm font-semibold bg-slate-900/90 text-blue-300 hover:text-white border border-blue-500/20 hover:border-blue-400 flex items-center gap-2 transition-all">
                <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.971.53 1.83.812 2.796.812 3.179 0 5.767-2.587 5.767-5.766.001-3.181-2.585-5.799-5.767-5.799z"/></svg>
                <span>Tanya Pakdhe Ardi</span>
            </a>
        </div>

        <!-- Scroll Down Mouse Indicator -->
        <div class="pt-10 flex flex-col items-center gap-2">
            <a href="#tentang" class="text-xs uppercase tracking-widest text-slate-400 hover:text-blue-300 transition-colors flex flex-col items-center gap-2">
                <span>Gulir ke Bawah</span>
                <div class="w-5 h-8 rounded-full border-2 border-blue-400/40 flex items-start justify-center p-1">
                    <div class="w-1.5 h-2 rounded-full bg-cyan-400 animate-bounce"></div>
                </div>
            </a>
        </div>

    </div>
</section>


<!-- ========================================================================= -->
<!-- 3. TENTANG SECTION (PRD §6.3 & FR-111, FR-112, FR-113, FR-114)            -->
<!-- ========================================================================= -->
<section id="tentang" class="relative py-28 px-4 sm:px-6 lg:px-8 border-t border-blue-900/30">
    <div class="max-w-7xl mx-auto space-y-16">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="text-xs font-bold tracking-widest uppercase text-blue-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
                Mengenal Lebih Dekat
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
                Tentang AOCC
            </h2>
            <p class="text-sm sm:text-base text-slate-300 leading-relaxed">
                <span class="text-blue-300 font-semibold">Alfa Omega Community Church</span> didirikan dengan kerinduan menghadirkan atmosfer hadirat Tuhan yang nyata, kasih persaudaraan yang tulus, dan kegerakan rohani bagi generasi.
            </p>
        </div>

        <!-- Visi & Misi Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Visi Card -->
            <div class="glass-panel p-8 sm:p-10 rounded-3xl border-blue-500/20 hover:border-blue-400/40 relative overflow-hidden group transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-white mb-3">Visi Kami</h3>
                <p class="text-slate-300 leading-relaxed text-sm sm:text-base">
                    {{ $about->vision ?? 'Menjadi gereja yang memulihkan, memperlengkapi, dan mengutus setiap jiwa untuk memancarkan kasih Kristus di tengah generasi ini.' }}
                </p>
            </div>

            <!-- Misi Card -->
            <div class="glass-panel p-8 sm:p-10 rounded-3xl border-blue-500/20 hover:border-blue-400/40 relative overflow-hidden group transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl sm:text-2xl font-bold text-white mb-3">Misi Kami</h3>
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base space-y-2">
                    {!! nl2br(e($about->mission ?? "1. Menghadirkan hadirat Tuhan melalui pujian & penyembahan.\n2. Memuridkan jemaat berakar kuat pada Firman.\n3. Membangun komunitas hangat dan saling menopang.")) !!}
                </div>
            </div>

        </div>

        <!-- Filosofi Pelayanan BETHEL Trigger Banner (PRD §6.3 & FR-112) -->
        <div class="glass-panel-glow p-8 sm:p-10 rounded-3xl border border-blue-500/30 relative overflow-hidden">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="space-y-3 text-center lg:text-left">
                    <span class="inline-block text-[11px] font-bold uppercase tracking-widest text-cyan-400 bg-blue-950/80 px-3 py-1 rounded-full border border-blue-500/30">
                        Nilai-Nilai Fondasi
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-bold text-white font-cinzel">
                        Filosofi Pelayanan — <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-cyan-300">BETHEL</span>
                    </h3>
                    <p class="text-sm text-slate-300 max-w-2xl">
                        Pelayanan AOCC dibangun di atas 6 pilar BETHEL: <em>Biblical Foundation, Excellence, Transformational Community, Heart of Worship, Empowerment, dan Love in Action</em>.
                    </p>
                </div>
                <button id="btn-open-bethel" 
                        type="button" 
                        class="px-8 py-3.5 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-xl shadow-blue-600/30 hover:scale-105 active:scale-95 transition-all whitespace-nowrap shrink-0">
                    Buka Filosofi BETHEL &rarr;
                </button>
            </div>
        </div>

        <!-- Pastor Profile & Contact Person Pakdhe Ardi (PRD §6.3, FR-113, FR-114) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            
            <!-- Pastor Profile Card (2 Cols) -->
            <div class="lg:col-span-2 glass-panel p-8 sm:p-10 rounded-3xl border-blue-500/20 flex flex-col sm:flex-row gap-8 items-center">
                <div class="relative w-36 h-36 sm:w-44 sm:h-44 rounded-2xl overflow-hidden shrink-0 border-2 border-blue-400/40 shadow-xl shadow-blue-500/20">
                    <img src="{{ $primaryPastor->photo_path ?? 'https://images.unsplash.com/photo-1544717305-2782549b5136?auto=format&fit=crop&w=800&q=80' }}" 
                         alt="{{ $primaryPastor->name }}" 
                         class="w-full h-full object-cover">
                </div>
                <div class="space-y-3 text-center sm:text-left">
                    <span class="text-xs uppercase tracking-widest font-bold text-blue-400">Gembala Sidang</span>
                    <h4 class="text-2xl font-bold text-white font-cinzel">{{ $primaryPastor->name }}</h4>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        {{ $primaryPastor->bio ?? 'Melayani dengan hati kebapaan dan kerinduan mendalam melihat setiap jiwa mengalami kasih Bapa yang sejati dan bertumbuh dalam kebenaran Firman Allah.' }}
                    </p>
                    <p class="text-xs text-blue-300 italic pt-1">
                        "Rumah Tuhan selalu terbuka lebar bagi siapa pun yang rindu pulang ke hadapan-Nya."
                    </p>
                </div>
            </div>

            <!-- Pakdhe Ardi Contact Person Card (1 Col) (FR-114) -->
            <div class="glass-panel p-8 rounded-3xl border-blue-500/20 flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-950/60 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shadow-md">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.971.53 1.83.812 2.796.812 3.179 0 5.767-2.587 5.767-5.766.001-3.181-2.585-5.799-5.767-5.799z"/></svg>
                    </div>
                    <span class="text-[11px] font-bold tracking-wider uppercase text-emerald-400">Layanan Jemaat & Konseling</span>
                    <h4 class="text-xl font-bold text-white">{{ $pakdheArdi->name ?? 'Pakdhe Ardi' }}</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Pertama kali berkunjung atau butuh dukungan doa dan informasi seputar pelayanan gereja? Pakdhe Ardi siap menyapa Anda dengan hangat.
                    </p>
                </div>
                <a href="{{ $pakdheArdi->whatsapp_url }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-full py-3 rounded-full text-xs font-bold text-center bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white shadow-lg shadow-emerald-600/30 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                    <span>Chat WhatsApp Pakdhe Ardi</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

        </div>

    </div>
</section>


<!-- ========================================================================= -->
<!-- 4. IBADAH SECTION (PRD §6.3 Fullscreen: 6 Jenis Ibadah Switcher)           -->
<!-- ========================================================================= -->
<section id="ibadah" class="relative min-h-screen flex items-center py-24 px-4 sm:px-6 lg:px-8 border-t border-blue-900/30">
    <div class="max-w-7xl mx-auto w-full space-y-12">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="text-xs font-bold tracking-widest uppercase text-blue-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
                Jadwal & Komunitas Ibadah
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
                6 Jenis Ibadah AOCC
            </h2>
            <p class="text-sm sm:text-base text-slate-300">
                Pilih kategori ibadah di bawah untuk melihat jadwal, lokasi, dan informasi lengkap tanpa memuat ulang halaman.
            </p>
        </div>

        <!-- 6 Tabs Selector (PRD §6.3: Ibadah Raya, Youth, Teens, Kids, Kaum Pria, Kaum Wanita) -->
        <div class="flex items-center justify-start md:justify-center gap-2 overflow-x-auto pb-4 no-scrollbar">
            @foreach($ibadahCategories as $index => $cat)
                @php
                    $isActive = ($index === 0);
                    $service = $cat->services->first();
                @endphp
                <button type="button" 
                        data-ibadah-btn="{{ $cat->slug }}"
                        class="ibadah-tab-btn px-5 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-300 {{ $isActive ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'bg-slate-900/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-blue-500/20' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Interactive Switcher Display Card -->
        @foreach($ibadahCategories as $index => $cat)
            @php
                $service = $cat->services->first();
                $cp = $service?->contactPerson ?? $pakdheArdi;
            @endphp
            <div id="ibadah-panel-{{ $cat->slug }}" 
                 class="ibadah-panel {{ $index === 0 ? '' : 'hidden' }} transition-opacity duration-500">
                <div class="glass-panel-glow rounded-3xl overflow-hidden border border-blue-500/30 grid grid-cols-1 lg:grid-cols-12 items-stretch">
                    
                    <!-- Left Photo -->
                    <div class="lg:col-span-5 relative min-h-[300px] lg:min-h-[460px] overflow-hidden">
                        <img src="{{ $service?->photo_path ?? 'https://images.unsplash.com/photo-1438232992991-995b7058bbb3?auto=format&fit=crop&w=1200&q=80' }}" 
                             alt="{{ $service?->title ?? $cat->name }}" 
                             class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-slate-950"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-600 text-white shadow-md">
                                {{ $cat->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Right Info Details -->
                    <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-cinzel">
                                {{ $service?->title ?? $cat->name }}
                            </h3>
                            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                                {{ $service?->description ?? 'Ibadah perjumpaan dengan hadirat Allah melalui pujian penyembahan dan pengajaran Firman Tuhan yang relevan.' }}
                            </p>

                            <!-- Key Specs Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                                <div class="glass-panel p-4 rounded-2xl border-blue-500/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-600/30 text-blue-400 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Waktu & Hari</span>
                                            <p class="text-xs sm:text-sm font-semibold text-white">{{ $service?->day_of_week }}, {{ $service?->start_time }} - {{ $service?->end_time }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="glass-panel p-4 rounded-2xl border-blue-500/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-cyan-600/30 text-cyan-300 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Lokasi / Ruangan</span>
                                            <p class="text-xs sm:text-sm font-semibold text-white">{{ $service?->location ?? 'Main Sanctuary AOCC' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Person WhatsApp Direct Link -->
                        <div class="pt-4 border-t border-blue-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="text-xs text-slate-400">
                                <span>Contact Person:</span>
                                <span class="text-white font-semibold ml-1">{{ $cp->name }} ({{ $cp->role }})</span>
                            </div>
                            <a href="{{ $cp->whatsapp_url }}" 
                               target="_blank" 
                               class="px-6 py-2.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-lg shadow-blue-500/25 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.971.53 1.83.812 2.796.812 3.179 0 5.767-2.587 5.767-5.766.001-3.181-2.585-5.799-5.767-5.799z"/></svg>
                                <span>Tanya Jadwal via WhatsApp</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

    </div>
</section>


<!-- ========================================================================= -->
<!-- 5. BERANDA SECTION (PRD §6.3 Fullscreen: Youth, Pro-M, Keluarga)           -->
<!-- ========================================================================= -->
<section id="beranda" class="relative min-h-screen flex items-center py-24 px-4 sm:px-6 lg:px-8 border-t border-blue-900/30">
    <div class="max-w-7xl mx-auto w-full space-y-12">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="text-xs font-bold tracking-widest uppercase text-cyan-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
                Komunitas Sel & Care Group
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
                Beranda Komunitas
            </h2>
            <p class="text-sm sm:text-base text-slate-300">
                Tempat bertumbuh dalam kelompok kecil yang intim, saling menopang dalam doa, dan menguatkan iman sehari-hari.
            </p>
        </div>

        <!-- 3 Tabs Selector (PRD §6.3: Youth, Pro-M, Keluarga) -->
        <div class="flex items-center justify-center gap-3 overflow-x-auto pb-4 no-scrollbar">
            @foreach($berandaCategories as $index => $bCat)
                @php $isBActive = ($index === 0); @endphp
                <button type="button" 
                        data-beranda-btn="{{ $bCat->slug }}"
                        class="beranda-tab-btn px-6 py-2.5 rounded-full text-xs sm:text-sm font-semibold whitespace-nowrap transition-all duration-300 {{ $isBActive ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/30' : 'bg-slate-900/80 text-slate-400 hover:text-white hover:bg-slate-800 border border-blue-500/20' }}">
                    {{ $bCat->name }}
                </button>
            @endforeach
        </div>

        <!-- Interactive Switcher Display Card -->
        @foreach($berandaCategories as $index => $bCat)
            @php
                $bService = $bCat->services->first();
                $bCp = $bService?->contactPerson ?? $pakdheArdi;
            @endphp
            <div id="beranda-panel-{{ $bCat->slug }}" 
                 class="beranda-panel {{ $index === 0 ? '' : 'hidden' }} transition-opacity duration-500">
                <div class="glass-panel-glow rounded-3xl overflow-hidden border border-blue-500/30 grid grid-cols-1 lg:grid-cols-12 items-stretch">
                    
                    <!-- Left Photo -->
                    <div class="lg:col-span-5 relative min-h-[300px] lg:min-h-[460px] overflow-hidden">
                        <img src="{{ $bService?->photo_path ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80' }}" 
                             alt="{{ $bService?->title ?? $bCat->name }}" 
                             class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent lg:bg-gradient-to-r lg:from-transparent lg:to-slate-950"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-cyan-600 text-white shadow-md">
                                {{ $bCat->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Right Info Details -->
                    <div class="lg:col-span-7 p-6 sm:p-10 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">
                            <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-cinzel">
                                {{ $bService?->title ?? $bCat->name }}
                            </h3>
                            <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                                {{ $bService?->description ?? 'Wadah persekutuan kelompok kecil untuk saling mengasah, mendoakan, dan bertumbuh dalam kasih karunia Tuhan.' }}
                            </p>

                            <!-- Key Specs Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                                <div class="glass-panel p-4 rounded-2xl border-blue-500/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-600/30 text-blue-400 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Jadwal Pertemuan</span>
                                            <p class="text-xs sm:text-sm font-semibold text-white">{{ $bService?->day_of_week }}, {{ $bService?->start_time }} WIB</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="glass-panel p-4 rounded-2xl border-blue-500/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-cyan-600/30 text-cyan-300 flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div>
                                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Titik Kumpul (Hub)</span>
                                            <p class="text-xs sm:text-sm font-semibold text-white">{{ $bService?->location ?? 'Various Hubs & Online' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Join Button -->
                        <div class="pt-4 border-t border-blue-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="text-xs text-slate-400">
                                <span>Koordinator Komunitas:</span>
                                <span class="text-white font-semibold ml-1">{{ $bCp->name }}</span>
                            </div>
                            <a href="{{ $bCp->whatsapp_url }}" 
                               target="_blank" 
                               class="px-6 py-2.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-lg shadow-blue-500/25 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.971.53 1.83.812 2.796.812 3.179 0 5.767-2.587 5.767-5.766.001-3.181-2.585-5.799-5.767-5.799z"/></svg>
                                <span>Gabung Beranda via WhatsApp</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

    </div>
</section>


<!-- ========================================================================= -->
<!-- 6. SERMON SECTION (PRD §6.3 Carousel 3 Card + "Lihat Selengkapnya")       -->
<!-- ========================================================================= -->
<section id="sermon" class="relative py-28 px-4 sm:px-6 lg:px-8 border-t border-blue-900/30">
    <div class="max-w-7xl mx-auto space-y-12">
        
        <!-- Header with Controls & "Lihat Selengkapnya" -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-3">
                <span class="text-xs font-bold tracking-widest uppercase text-blue-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
                    Firman & Pengajaran
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
                    Khotbah Terbaru (Sermon)
                </h2>
                <p class="text-sm sm:text-base text-slate-300 max-w-2xl">
                    Dengarkan pesan Firman Tuhan yang menguatkan dan mengarahkan hidup Anda dalam kebenaran Kristus.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button id="sermon-prev" type="button" class="w-10 h-10 rounded-full glass-panel flex items-center justify-center text-slate-300 hover:text-white hover:border-blue-400 transition-colors" aria-label="Previous Sermon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button id="sermon-next" type="button" class="w-10 h-10 rounded-full glass-panel flex items-center justify-center text-slate-300 hover:text-white hover:border-blue-400 transition-colors" aria-label="Next Sermon">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <a href="{{ route('sermons.index') }}" class="ml-2 px-5 py-2.5 rounded-full text-xs font-bold bg-blue-950/80 hover:bg-blue-900/80 text-blue-300 border border-blue-500/30 transition-all hover:border-blue-400 whitespace-nowrap">
                    Lihat Selengkapnya &rarr;
                </a>
            </div>
        </div>

        <!-- Swiper Carousel Container (3 Cards on Desktop as per PRD §6.3) -->
        <div class="swiper sermon-swiper overflow-hidden pb-4">
            <div class="swiper-wrapper">
                @foreach($sermons as $sermon)
                    <div class="swiper-slide h-auto">
                        <div class="h-full glass-panel rounded-3xl overflow-hidden border-blue-500/20 hover:border-blue-400/50 hover:shadow-[0_0_30px_rgba(59,130,246,0.25)] transition-all flex flex-col group">
                            
                            <!-- Video Thumbnail with YouTube trigger -->
                            <div class="relative aspect-video overflow-hidden bg-slate-950 cursor-pointer btn-open-sermon"
                                 data-youtube-id="{{ $sermon->youtube_id ?? 'dQw4w9WgXcQ' }}"
                                 data-sermon-title="{{ $sermon->title }}"
                                 data-sermon-speaker="{{ $sermon->speaker_name }}"
                                 data-sermon-ref="{{ $sermon->scripture_ref }}">
                                <img src="{{ $sermon->thumbnail_path ?? 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="{{ $sermon->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/20 transition-colors flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full bg-blue-600/90 text-white flex items-center justify-center shadow-lg shadow-blue-500/50 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>

                                <div class="absolute top-3 left-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-950/90 text-cyan-300 border border-blue-500/30">
                                        {{ $sermon->scripture_ref }}
                                    </span>
                                </div>
                            </div>

                            <!-- Sermon Card Content (PRD §6.3: Judul, Firman, Tanggal, Pembawa Firman, Ibadah, Sumber YouTube) -->
                            <div class="p-6 flex flex-col justify-between flex-1 space-y-4">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span>{{ \Carbon\Carbon::parse($sermon->preached_at)->translatedFormat('d M Y') }}</span>
                                        <span class="text-blue-400 font-medium">{{ $sermon->category?->name ?? 'Ibadah Raya' }}</span>
                                    </div>
                                    <h3 class="text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition-colors line-clamp-2">
                                        {{ $sermon->title }}
                                    </h3>
                                    <p class="text-xs text-slate-400 line-clamp-2">
                                        {{ $sermon->description ?? 'Pesan kebenaran Firman Tuhan yang membangkitkan iman dan damai sejahtera.' }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-blue-900/40 flex items-center justify-between text-xs">
                                    <span class="font-medium text-slate-300 truncate max-w-[170px]">{{ $sermon->speaker_name }}</span>
                                    <button type="button" 
                                            class="text-blue-400 hover:text-cyan-300 font-semibold flex items-center gap-1 btn-open-sermon"
                                            data-youtube-id="{{ $sermon->youtube_id ?? 'dQw4w9WgXcQ' }}"
                                            data-sermon-title="{{ $sermon->title }}"
                                            data-sermon-speaker="{{ $sermon->speaker_name }}"
                                            data-sermon-ref="{{ $sermon->scripture_ref }}">
                                        <span>Tonton</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>


<!-- ========================================================================= -->
<!-- 7. ACARA SECTION (PRD §6.3 Carousel 3 Card + "Lihat Selengkapnya")         -->
<!-- ========================================================================= -->
<section id="acara" class="relative py-28 px-4 sm:px-6 lg:px-8 border-t border-blue-900/30">
    <div class="max-w-7xl mx-auto space-y-12">
        
        <!-- Header with Controls & "Lihat Selengkapnya" -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-3">
                <span class="text-xs font-bold tracking-widest uppercase text-cyan-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
                    Kalender & Agenda
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
                    Acara Mendatang (Events)
                </h2>
                <p class="text-sm sm:text-base text-slate-300 max-w-2xl">
                    Ikuti berbagai kegiatan, persekutuan spesial, dan seminar pertumbuhan rohani di AOCC.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button id="event-prev" type="button" class="w-10 h-10 rounded-full glass-panel flex items-center justify-center text-slate-300 hover:text-white hover:border-blue-400 transition-colors" aria-label="Previous Event">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button id="event-next" type="button" class="w-10 h-10 rounded-full glass-panel flex items-center justify-center text-slate-300 hover:text-white hover:border-blue-400 transition-colors" aria-label="Next Event">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <a href="{{ route('events.index') }}" class="ml-2 px-5 py-2.5 rounded-full text-xs font-bold bg-blue-950/80 hover:bg-blue-900/80 text-blue-300 border border-blue-500/30 transition-all hover:border-blue-400 whitespace-nowrap">
                    Lihat Selengkapnya &rarr;
                </a>
            </div>
        </div>

        <!-- Swiper Carousel Container (3 Cards on Desktop as per PRD §6.3) -->
        <div class="swiper event-swiper overflow-hidden pb-4">
            <div class="swiper-wrapper">
                @foreach($events as $event)
                    @php
                        $eventCp = $event->contactPerson ?? $pakdheArdi;
                    @endphp
                    <div class="swiper-slide h-auto">
                        <div class="h-full glass-panel rounded-3xl overflow-hidden border-blue-500/20 hover:border-blue-400/50 hover:shadow-[0_0_30px_rgba(59,130,246,0.25)] transition-all flex flex-col group">
                            
                            <!-- Event Cover -->
                            <div class="relative aspect-video overflow-hidden bg-slate-950">
                                <img src="{{ $event->cover_path ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80' }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>

                                <!-- Date Badge Overlay -->
                                <div class="absolute top-3 left-3 bg-blue-950/90 border border-blue-500/30 rounded-xl px-3 py-1.5 text-center shadow-lg">
                                    <span class="block text-xs uppercase font-extrabold text-cyan-300">
                                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M') }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400">
                                        {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Content (PRD §6.3: Nama, Tanggal, Tempat, Waktu, Deskripsi, Link Terkait, Contact Person) -->
                            <div class="p-6 flex flex-col justify-between flex-1 space-y-4">
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2 text-[11px] text-blue-300 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>{{ $event->start_time }} {{ $event->end_time ? '- '.$event->end_time : '' }}</span>
                                    </div>

                                    <h3 class="text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition-colors line-clamp-2">
                                        {{ $event->title }}
                                    </h3>

                                    <p class="text-xs text-slate-400 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <span class="truncate">{{ $event->venue }}</span>
                                    </p>

                                    <p class="text-xs text-slate-300 line-clamp-2">
                                        {{ $event->description }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-blue-900/40 flex items-center justify-between text-xs">
                                    <span class="text-slate-400 text-[11px]">CP: {{ $eventCp->name }}</span>
                                    <a href="{{ $event->link_url ?? $eventCp->whatsapp_url }}" 
                                       target="_blank" 
                                       class="px-3.5 py-1.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white font-semibold text-[11px] transition-all flex items-center gap-1">
                                        <span>{{ $event->link_label ?? 'Reservasi / Info' }}</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</section>

@endsection
