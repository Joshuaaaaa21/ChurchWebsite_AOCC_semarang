@extends('layouts.app')

@section('title', 'Agenda & Acara — Alfa Omega Community Church (AOCC)')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">
    
    <!-- Header -->
    <div class="text-center max-w-3xl mx-auto space-y-4">
        <span class="text-xs font-bold tracking-widest uppercase text-cyan-400 bg-blue-950/60 px-3.5 py-1 rounded-full border border-blue-500/25">
            Kalender Kegiatan
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-white font-cinzel">
            Agenda Acara AOCC
        </h1>
        <p class="text-sm sm:text-base text-slate-300">
            Jadwal kegiatan gereja, malam penyembahan, retret pemuda, hingga seminar pembinaan keluarga.
        </p>
    </div>

    <!-- Search Bar -->
    <div class="glass-panel p-4 sm:p-6 rounded-3xl border border-blue-500/25">
        <form action="{{ route('events.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input type="text" 
                       name="q" 
                       value="{{ $searchQuery }}" 
                       placeholder="Cari nama acara, tempat, atau deskripsi..." 
                       class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-900/90 border border-blue-500/20 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-blue-400 transition-colors">
                <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <button type="submit" class="w-full sm:w-auto px-7 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-semibold text-xs sm:text-sm hover:from-blue-500 hover:to-cyan-400 shadow-md transition-all whitespace-nowrap">
                Cari Acara
            </button>
            @if($searchQuery)
                <a href="{{ route('events.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-2xl bg-slate-800 text-slate-300 text-xs hover:text-white transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Events Grid (9 per Page) -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($events as $event)
                @php
                    $cp = $event->contactPerson;
                @endphp
                <div class="glass-panel rounded-3xl overflow-hidden border-blue-500/20 hover:border-blue-400/50 hover:shadow-[0_0_30px_rgba(59,130,246,0.25)] transition-all flex flex-col group">
                    
                    <!-- Cover Image & Date Badge -->
                    <div class="relative aspect-video overflow-hidden bg-slate-950">
                        <img src="{{ $event->cover_path ?? 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=800&q=80' }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></div>

                        <div class="absolute top-3 left-3 bg-blue-950/90 border border-blue-500/30 rounded-xl px-3 py-1.5 text-center shadow-lg">
                            <span class="block text-xs uppercase font-extrabold text-cyan-300">
                                {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d M') }}
                            </span>
                            <span class="block text-[10px] text-slate-400">
                                {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-6 flex flex-col justify-between flex-1 space-y-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-2 text-[11px] text-blue-300 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ $event->start_time }} {{ $event->end_time ? '- '.$event->end_time : '' }}</span>
                            </div>

                            <h3 class="text-base sm:text-lg font-bold text-white group-hover:text-blue-300 transition-colors line-clamp-2">
                                <a href="{{ route('events.show', $event->slug) }}">
                                    {{ $event->title }}
                                </a>
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
                            <a href="{{ route('events.show', $event->slug) }}" class="text-slate-400 hover:text-white text-xs">
                                Detail Acara
                            </a>
                            <a href="{{ $event->link_url ?? ($cp ? $cp->whatsapp_url : 'https://wa.me/6281288997766') }}" 
                               target="_blank" 
                               class="px-3.5 py-1.5 rounded-full bg-blue-600 hover:bg-blue-500 text-white font-semibold text-[11px] transition-all flex items-center gap-1">
                                <span>{{ $event->link_label ?? 'Reservasi / Info' }}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pt-8">
            {{ $events->links() }}
        </div>
    @else
        <div class="glass-panel p-12 rounded-3xl text-center space-y-4 max-w-lg mx-auto">
            <svg class="w-12 h-12 text-slate-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <h3 class="text-lg font-bold text-white">Tidak ada acara yang ditemukan</h3>
            <p class="text-xs text-slate-400">Silakan periksa kembali kata kunci pencarian Anda.</p>
            <a href="{{ route('events.index') }}" class="inline-block px-5 py-2 rounded-full text-xs font-semibold bg-blue-600 text-white">
                Tampilkan Semua
            </a>
        </div>
    @endif

</div>
@endsection
