<!-- YouTube Player Modal -->
<div id="youtube-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none transition-all duration-300">
    
    <!-- Backdrop Blur with dark blue overlay -->
    <div id="youtube-modal-backdrop" class="absolute inset-0 bg-slate-950/85 backdrop-blur-xl transition-opacity duration-300"></div>

    <!-- Modal Content Card -->
    <div class="relative z-10 w-full max-w-4xl rounded-3xl glass-panel-glow p-4 sm:p-6 border border-blue-500/30 shadow-[0_0_60px_rgba(37,99,235,0.4)] transform scale-95 transition-transform duration-300">
        
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-blue-900/40 mb-4">
            <div class="truncate pr-4">
                <span id="youtube-modal-badge" class="text-[10px] uppercase font-bold tracking-widest text-blue-400 bg-blue-950/80 px-2.5 py-0.5 rounded-full border border-blue-500/30">
                    Khotbah AOCC
                </span>
                <h4 id="youtube-modal-title" class="text-base sm:text-lg font-bold text-white truncate mt-1">
                    Judul Khotbah
                </h4>
            </div>
            <button id="btn-close-youtube" 
                    type="button" 
                    class="p-2 rounded-full bg-slate-900/80 border border-blue-500/20 text-slate-400 hover:text-white hover:bg-blue-900/40 transition-colors focus:outline-none shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- 16:9 Video Embed Container -->
        <div class="relative w-full aspect-video rounded-2xl overflow-hidden bg-black border border-blue-500/20 shadow-inner">
            <iframe id="youtube-modal-iframe" 
                    class="absolute inset-0 w-full h-full" 
                    src="" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                    allowfullscreen>
            </iframe>
        </div>

        <!-- Footer / Preacher info -->
        <div class="mt-4 flex items-center justify-between text-xs text-slate-400 px-1">
            <span id="youtube-modal-speaker" class="font-medium text-slate-300">Pembawa Firman</span>
            <span id="youtube-modal-ref" class="text-blue-400 font-semibold font-serif italic">Nats Alkitab</span>
        </div>
    </div>
</div>
