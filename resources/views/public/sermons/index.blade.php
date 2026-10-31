@extends('layouts.app')

@section('title', 'Arsip Khotbah (Sermons) — Alfa Omega Community Church (AOCC)')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">
    
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold tracking-widest uppercase text-blue-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
            Perpustakaan Digital Firman
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
            Arsip Khotbah AOCC
        </h1>
        <p class="text-sm sm:text-base text-slate-300">
            Temukan rekaman khotbah mingguan, seri pengajaran, dan pesan Firman yang relevan bagi kehidupan sehari-hari.
        </p>
    </div>

    <!-- Search & Filter Controls -->
    <div class="glass-panel p-4 sm:p-6 rounded-3xl border border-blue-500/25 space-y-4">
        <form action="{{ route('sermons.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4">
            
            <!-- Search Input -->
            <div class="md:col-span-8 relative">
                <input type="text" 
                       name="q" 
                       value="{{ $searchQuery }}" 
                       placeholder="Cari judul khotbah, pembawa firman, ayat, atau topik..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-900/90 border border-blue-500/20 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-400 transition-colors">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            <!-- Submit & Reset Buttons -->
            <div class="md:col-span-4 flex items-center gap-2">
                <button type="submit" class="flex-1 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold text-xs sm:text-sm hover:from-blue-500 hover:to-cyan-400 shadow-md transition-all">
                    Cari Khotbah
                </button>
                @if($searchQuery || $selectedCategory)
                    <a href="{{ route('sermons.index') }}" class="px-4 py-3 rounded-2xl bg-slate-800 text-slate-300 text-xs hover:text-white transition-colors">
                        Reset
                    </a>
                @endif
            </div>

        </form>

        <!-- Category Pills Filter -->
        <div class="flex items-center gap-2 overflow-x-auto pt-2 pb-1 no-scrollbar">
            <a href="{{ route('sermons.index', array_filter(['q' => $searchQuery])) }}" 
               class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ empty($selectedCategory) ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-900/80 text-slate-400 hover:text-white border border-blue-500/20' }}">
                Semua Kategori
            </a>
            @foreach($categories as $category)
                <a href="{{ route('sermons.index', array_filter(['q' => $searchQuery, 'kategori' => $category->slug])) }}" 
                   class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors {{ $selectedCategory === $category->slug ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-900/80 text-slate-400 hover:text-white border border-blue-500/20' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Sermons Grid (9 Cards per Page) -->
    @if($sermons->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($sermons as $sermon)
                <div class="glass-panel rounded-3xl overflow-hidden border-blue-500/20 hover:border-blue-400/50 hover:shadow-[0_0_30px_rgba(59,130,246,0.25)] transition-all flex flex-col group">
                    
                    <!-- Video Thumbnail with YouTube Trigger -->
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

                    <!-- Sermon Content -->
                    <div class="p-6 flex flex-col justify-between flex-1 space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-[11px] text-slate-400">
                                <span>{{ \Carbon\Carbon::parse($sermon->preached_at)->translatedFormat('d M Y') }}</span>
                                <span class="text-blue-400 font-medium">{{ $sermon->category?->name ?? 'Ibadah Raya' }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-white group-hover:text-blue-300 transition-colors line-clamp-2">
                                <a href="{{ route('sermons.show', $sermon->slug) }}">
                                    {{ $sermon->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-slate-400 line-clamp-2">
                                {{ $sermon->description }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-blue-900/40 flex items-center justify-between text-xs">
                            <span class="font-medium text-slate-300 truncate max-w-[170px]">{{ $sermon->speaker_name }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('sermons.show', $sermon->slug) }}" class="text-slate-400 hover:text-white text-xs">
                                    Detail
                                </a>
                                <button type="button" 
                                        class="px-3 py-1 rounded-full bg-blue-600/80 hover:bg-blue-500 text-white font-semibold text-[11px] btn-open-sermon"
                                        data-youtube-id="{{ $sermon->youtube_id ?? 'dQw4w9WgXcQ' }}"
                                        data-sermon-title="{{ $sermon->title }}"
                                        data-sermon-speaker="{{ $sermon->speaker_name }}"
                                        data-sermon-ref="{{ $sermon->scripture_ref }}">
                                    Putar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-8">
            {{ $sermons->links() }}
        </div>
    @else
        <div class="glass-panel p-12 rounded-3xl text-center space-y-4 max-w-lg mx-auto">
            <svg class="w-12 h-12 text-slate-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-lg font-bold text-white">Tidak ada khotbah yang cocok</h3>
            <p class="text-xs text-slate-400">Silakan coba kata kunci pencarian lain atau pilih kategori yang berbeda.</p>
            <a href="{{ route('sermons.index') }}" class="inline-block px-5 py-2 rounded-full text-xs font-semibold bg-blue-600 text-white">
                Tampilkan Semua
            </a>
        </div>
    @endif

</div>
@endsection
