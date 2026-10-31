@extends('layouts.app')

@section('title', $sermon->title . ' — AOCC Sermon')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-10">
    
    <!-- Breadcrumb & Back -->
    <div class="flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-blue-300">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('sermons.index') }}" class="hover:text-blue-300">Khotbah</a>
        <span>&rsaquo;</span>
        <span class="text-white truncate max-w-xs">{{ $sermon->title }}</span>
    </div>

    <!-- Main Video Player Section -->
    <div class="glass-panel-glow rounded-3xl overflow-hidden border border-blue-500/30 p-4 sm:p-6 space-y-6">
        
        <div class="relative aspect-video rounded-2xl overflow-hidden bg-black border border-blue-500/20 shadow-2xl">
            <iframe class="absolute inset-0 w-full h-full" 
                    src="https://www.youtube.com/embed/{{ $sermon->youtube_id ?? 'dQw4w9WgXcQ' }}" 
                    title="{{ $sermon->title }}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
            </iframe>
        </div>

        <div class="space-y-4 px-2">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-600/30 text-cyan-300 border border-blue-400/30">
                    {{ $sermon->scripture_ref }}
                </span>
                <span class="text-xs text-slate-400">
                    Dibagikan pada {{ \Carbon\Carbon::parse($sermon->preached_at)->translatedFormat('l, d F Y') }}
                </span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-extrabold text-white font-cinzel">
                {{ $sermon->title }}
            </h1>

            <div class="flex items-center gap-4 py-2 border-y border-blue-900/40 text-xs sm:text-sm text-slate-300">
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Pembicara</span>
                    <span class="text-white font-semibold">{{ $sermon->speaker_name }}</span>
                </div>
                <div class="h-6 w-px bg-blue-900/50"></div>
                <div>
                    <span class="text-slate-500 block text-[10px] uppercase font-bold">Pelayanan</span>
                    <span class="text-blue-300">{{ $sermon->category?->name ?? 'Ibadah Raya' }}</span>
                </div>
            </div>

            <div class="text-slate-300 text-sm sm:text-base leading-relaxed space-y-3 pt-2">
                <p>{{ $sermon->description ?? 'Khotbah yang mengupas secara mendalam ayat-ayat Alkitab dan penerapannya dalam kehidupan praktis sehari-hari.' }}</p>
            </div>
        </div>

    </div>

    <!-- Related Sermons -->
    @if($relatedSermons->isNotEmpty())
        <div class="space-y-6 pt-6">
            <h3 class="text-xl sm:text-2xl font-bold text-white font-cinzel">
                Khotbah Terkait Lainnya
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedSermons as $rel)
                    <div class="glass-panel rounded-2xl overflow-hidden border-blue-500/20 hover:border-blue-400/40 transition-all flex flex-col">
                        <a href="{{ route('sermons.show', $rel->slug) }}" class="relative aspect-video block overflow-hidden">
                            <img src="{{ $rel->thumbnail_path ?? 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=600&q=80' }}" 
                                 alt="{{ $rel->title }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-2">
                            <span class="text-[10px] text-cyan-400 font-bold uppercase">{{ $rel->scripture_ref }}</span>
                            <h4 class="text-sm font-bold text-white hover:text-blue-300 transition-colors line-clamp-2">
                                <a href="{{ route('sermons.show', $rel->slug) }}">{{ $rel->title }}</a>
                            </h4>
                            <span class="text-[11px] text-slate-400">{{ $rel->speaker_name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
