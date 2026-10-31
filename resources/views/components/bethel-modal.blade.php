<!-- BETHEL Philosophy Modal (Glassmorphism as per PRD §6.3 & FR-112) -->
<div id="bethel-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 opacity-0 pointer-events-none transition-all duration-300">
    
    <!-- Backdrop Blur with dark blue overlay -->
    <div id="bethel-modal-backdrop" class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl transition-opacity duration-300"></div>

    <!-- Modal Content Card -->
    <div class="relative z-10 w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl glass-panel-glow p-6 sm:p-10 border border-blue-500/30 shadow-[0_0_50px_rgba(37,99,235,0.3)] transform scale-95 transition-transform duration-300">
        
        <!-- Header -->
        <div class="flex items-start justify-between pb-6 border-b border-blue-900/50">
            <div>
                <span class="inline-block text-[11px] font-bold tracking-widest uppercase text-blue-400 bg-blue-950/80 px-3 py-1 rounded-full border border-blue-500/30 mb-2">
                    Nilai & Prinsip Inti AOCC
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-white font-cinzel tracking-wide flex items-center gap-3">
                    <span>Filosofi Pelayanan</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-500">BETHEL</span>
                </h3>
                <p class="text-sm text-slate-300 mt-2 max-w-2xl">
                    Landasan spiritual dan kompas pelayanan Alfa Omega Community Church dalam mendampingi jemaat bertumbuh menuju kedewasaan penuh di dalam Kristus.
                </p>
            </div>
            <button id="btn-close-bethel" 
                    type="button" 
                    class="p-2.5 rounded-full bg-slate-900/80 border border-blue-500/20 text-slate-400 hover:text-white hover:bg-blue-900/40 transition-colors focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- 6 Pillars of BETHEL Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-6">
            
            <!-- B -->
            <div class="glass-panel p-5 rounded-2xl border-blue-500/20 hover:border-blue-400/40 hover:bg-blue-950/20 transition-all group">
                <div class="flex items-center gap-3.5 mb-2.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        B
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-base">Biblical Foundation</h4>
                        <span class="text-xs text-blue-400 font-medium">Berakar pada Firman</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Alkitab adalah otoritas tertinggi, penuntun jalan, dan kebenaran mutlak yang mendasari setiap pengajaran, pemuridan, dan pengambilan keputusan di AOCC.
                </p>
            </div>

            <!-- E -->
            <div class="glass-panel p-5 rounded-2xl border-blue-500/20 hover:border-blue-400/40 hover:bg-blue-950/20 transition-all group">
                <div class="flex items-center gap-3.5 mb-2.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        E
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-base">Excellence in Service</h4>
                        <span class="text-xs text-blue-400 font-medium">Memberi yang Terbaik</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Keunggulan bukan tentang kesempurnaan manusia, melainkan penghormatan dan penyembahan tulus kami dengan mempersembahkan talenta dan dedikasi terbaik bagi Tuhan.
                </p>
            </div>

            <!-- T -->
            <div class="glass-panel p-5 rounded-2xl border-blue-500/20 hover:border-blue-400/40 hover:bg-blue-950/20 transition-all group">
                <div class="flex items-center gap-3.5 mb-2.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        T
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-base">Transformational Community</h4>
                        <span class="text-xs text-blue-400 font-medium">Komunitas yang Memulihkan</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Gereja sebagai rumah aman dan hangat di mana setiap orang diterima apa adanya, dipulihkan lukanya, dan ditransformasikan menjadi serupa dengan gambar Kristus.
                </p>
            </div>

            <!-- H -->
            <div class="glass-panel p-5 rounded-2xl border-blue-500/20 hover:border-blue-400/40 hover:bg-blue-950/20 transition-all group">
                <div class="flex items-center gap-3.5 mb-2.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        H
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-base">Heart of Worship</h4>
                        <span class="text-xs text-blue-400 font-medium">Gaya Hidup Menyembah</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Penyembahan bukan seremonial mingguan, melainkan detak jantung ketaatan dan keintiman pribadi bersama Allah yang tercermin dalam tindakan nyata setiap saat.
                </p>
            </div>

            <!-- E -->
            <div class="glass-panel p-5 rounded-2xl border-blue-500/20 hover:border-blue-400/40 hover:bg-blue-950/20 transition-all group">
                <div class="flex items-center gap-3.5 mb-2.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        E
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-base">Empowerment & Discipleship</h4>
                        <span class="text-xs text-blue-400 font-medium">Memperlengkapi Generasi</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Mempersiapkan generasi masa kini dan masa depan melalui mentoring rohani, impartasi kepemimpinan, dan pelibatan aktif dalam pelayanan Kerajaan Allah.
                </p>
            </div>

            <!-- L -->
            <div class="glass-panel p-5 rounded-2xl border-blue-500/20 hover:border-blue-400/40 hover:bg-blue-950/20 transition-all group">
                <div class="flex items-center gap-3.5 mb-2.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 flex items-center justify-center font-bold text-xl text-white shadow-lg shadow-blue-500/30 group-hover:scale-105 transition-transform">
                        L
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-base">Love in Action</h4>
                        <span class="text-xs text-blue-400 font-medium">Kasih yang Mengasihi</span>
                    </div>
                </div>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Kasih yang tidak berhenti pada kata-kata, tetapi melangkah nyata merangkul yang tersisih, memelihara kaum lemah, dan menjadi jawaban bagi lingkungan masyarakat.
                </p>
            </div>

        </div>

        <!-- Footer Modal -->
        <div class="mt-8 pt-5 border-t border-blue-900/40 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-400">
                Punya pertanyaan mengenai pemuridan atau pelayanan AOCC?
            </span>
            <a href="https://wa.me/6281288997766?text=Halo%20Pakdhe%20Ardi,%20saya%20ingin%20tahu%20lebih%20banyak%20tentang%20filosofi%20BETHEL%20AOCC" 
               target="_blank" 
               class="px-5 py-2 rounded-full text-xs font-semibold bg-blue-600 hover:bg-blue-500 text-white transition-all shadow-md shadow-blue-600/30">
                Tanya Pakdhe Ardi via WhatsApp
            </a>
        </div>
    </div>
</div>
