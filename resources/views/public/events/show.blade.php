@extends('layouts.app')

@section('title', $event->title . ' — AOCC Event')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto space-y-10">
    
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-blue-300">Beranda</a>
        <span>&rsaquo;</span>
        <a href="{{ route('events.index') }}" class="hover:text-blue-300">Acara</a>
        <span>&rsaquo;</span>
        <span class="text-white truncate max-w-xs">{{ $event->title }}</span>
    </div>

    <!-- Main Event Card -->
    <div class="glass-panel-glow rounded-3xl overflow-hidden border border-blue-500/30 p-6 sm:p-10 space-y-8">
        
        <!-- Cover Image -->
        <div class="relative aspect-[21/9] rounded-2xl overflow-hidden bg-slate-950 border border-blue-500/20 shadow-2xl">
            <img src="{{ $event->cover_path ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1200&q=80' }}" 
                 alt="{{ $event->title }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
            
            <div class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-blue-600 text-white shadow-lg">
                    {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y') }}
                </span>
            </div>
        </div>

        <div class="space-y-6">
            <h1 class="text-2xl sm:text-4xl font-extrabold text-white font-cinzel">
                {{ $event->title }}
            </h1>

            <!-- Key Info Box -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 rounded-2xl glass-panel border-blue-500/20 text-xs sm:text-sm">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Tanggal & Hari</span>
                    <span class="text-white font-semibold">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Waktu Pelaksanaan</span>
                    <span class="text-blue-300 font-semibold">{{ $event->start_time }} {{ $event->end_time ? '- '.$event->end_time : '' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Lokasi / Tempat</span>
                    <span class="text-cyan-300 font-semibold">{{ $event->venue }}</span>
                </div>
            </div>

            <!-- Description -->
            <div class="text-slate-300 text-sm sm:text-base leading-relaxed space-y-4">
                <h3 class="text-lg font-bold text-white font-cinzel">Tentang Acara Ini</h3>
                <p>{{ $event->description }}</p>
            </div>

            <!-- Action Button -->
            <div class="pt-6 border-t border-blue-900/40 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-xs text-slate-400">
                    <span>Contact Person:</span>
                    <span class="text-white font-semibold ml-1">{{ $event->contactPerson?->name ?? 'Pakdhe Ardi' }}</span>
                </div>
                <a href="{{ $event->link_url ?? ($event->contactPerson ? $event->contactPerson->whatsapp_url : 'https://wa.me/6281288997766') }}" 
                   target="_blank" 
                   class="px-8 py-3.5 rounded-full text-xs sm:text-sm font-bold bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white shadow-xl shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    <span>{{ $event->link_label ?? 'Reservasi / Konfirmasi Kehadiran' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

    </div>

    <!-- Other Upcoming Events -->
    @if($upcomingEvents->isNotEmpty())
        <div class="space-y-6 pt-6">
            <h3 class="text-xl sm:text-2xl font-bold text-white font-cinzel">
                Acara Mendatang Lainnya
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $up)
                    <div class="glass-panel rounded-2xl overflow-hidden border-blue-500/20 hover:border-blue-400/40 transition-all flex flex-col">
                        <a href="{{ route('events.show', $up->slug) }}" class="relative aspect-video block overflow-hidden">
                            <img src="{{ $up->cover_path ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=600&q=80' }}" 
                                 alt="{{ $up->title }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </a>
                        <div class="p-4 flex-1 flex flex-col justify-between space-y-2">
                            <span class="text-[10px] text-cyan-400 font-bold uppercase">{{ \Carbon\Carbon::parse($up->event_date)->translatedFormat('d M Y') }}</span>
                            <h4 class="text-sm font-bold text-white hover:text-blue-300 transition-colors line-clamp-2">
                                <a href="{{ route('events.show', $up->slug) }}">{{ $up->title }}</a>
                            </h4>
                            <span class="text-[11px] text-slate-400">{{ $up->venue }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
