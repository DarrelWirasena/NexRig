@extends('layouts.dashboard')

@section('content')

{{-- 
    DATA PROCESSING (PHP)
--}}
@php
    $safeHistory = $history->map(function($item) {
        return [
            'id' => $item->id,
            'subject' => $item->subject,
            'message' => $item->message,
            'status' => $item->status ?? 'sent', 
            'created_at' => $item->created_at,
            'date_formatted' => $item->created_at->format('d M Y'),
            'time_formatted' => $item->created_at->format('H:i'),
        ];
    });
@endphp

{{-- Tambahkan px-4 agar konten tidak mentok ke pinggir layar di mobile --}}
<div class="max-w-5xl mx-auto flex flex-col h-[calc(100vh-120px)] px-2 md:px-0" 
     x-data="{ 
        search: '', 
        sortBy: 'newest',
        selectedItem: null,
        items: {{ Js::from($safeHistory) }},
        
        get filteredItems() {
            let result = this.items.filter(i => {
                const searchLower = this.search.toLowerCase();
                const subject = (i.subject || '').toLowerCase();
                const message = (i.message || '').toLowerCase();
                return subject.includes(searchLower) || message.includes(searchLower);
            });

            if (this.sortBy === 'newest') return result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            return result.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
        },

        getStatusClass(status) {
            if (status === 'replied') return 'bg-green-500/10 text-green-500 border-green-500/20';
            if (status === 'closed') return 'bg-gray-500/10 text-gray-400 border-gray-500/20';
            return 'bg-blue-600/10 text-blue-500 border-blue-600/20';
        },

        getBadgeLabel(status) {
            if (status === 'replied') return 'DIBALAS';
            if (status === 'closed') return 'SELESAI';
            return 'TERKIRIM';
        },

        getStatusDescription(status) {
            if (status === 'replied') return 'Pesan Telah Dibalas Admin';
            if (status === 'closed') return 'Percakapan Selesai';
            return 'Menunggu Konfirmasi Admin'; 
        }
     }">
    
    {{-- HEADER --}}
    <div class="shrink-0 z-10 pb-6 border-b border-white/10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                {{-- text-2xl di mobile, text-4xl di desktop --}}
                <h1 class="text-2xl md:text-4xl font-black uppercase italic tracking-tighter leading-none text-white">
                    Riwayat <span class="text-blue-600">Pesan</span>
                </h1>
                <p class="text-gray-400 text-xs md:text-sm mt-2 font-display">Arsip komunikasi email dan bantuan teknis Anda.</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                {{-- SORT --}}
                <div class="flex items-center bg-[#0a0a0a] border border-white/10 rounded-lg px-3 group focus-within:border-blue-600 transition-colors w-full sm:w-auto">
                    <span class="material-symbols-outlined text-gray-500 text-sm">sort</span>
                    <select x-model="sortBy" class="bg-transparent text-gray-400 text-[10px] font-bold uppercase tracking-widest py-2 px-2 outline-none cursor-pointer border-none focus:ring-0 w-full">
                        <option value="newest" class="bg-[#0a0a0a]">Terbaru</option>
                        <option value="oldest" class="bg-[#0a0a0a]">Terlama</option>
                    </select>
                </div>

                {{-- SEARCH --}}
                <div class="relative w-full md:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <span class="material-symbols-outlined text-[20px]">search</span>
                    </span>
                    <input type="text" x-model="search" placeholder="Cari pesan..." 
                           class="w-full bg-[#0a0a0a] border border-white/10 pl-10 pr-4 py-2.5 rounded-lg text-sm text-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-600 outline-none transition-all">
                </div>
            </div>
        </div>
    </div>

    {{-- LIST DATA --}}
    <div class="flex-1 overflow-y-auto pr-1 mt-6 space-y-4 scrollbar-hide">
        <template x-for="item in filteredItems" :key="item.id">
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-5 md:p-6 hover:border-blue-600/50 transition-all group relative overflow-hidden">
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between gap-4 md:gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            {{-- BADGE STATUS --}}
                            <span :class="getStatusClass(item.status)" 
                                  class="px-2 py-0.5 text-[8px] md:text-[9px] font-black uppercase tracking-widest border rounded">
                                <span x-text="getBadgeLabel(item.status)"></span>
                            </span>
                            <span class="text-gray-600 text-[10px] font-mono uppercase tracking-widest">
                                #PESAN-<span x-text="String(item.id).padStart(4, '0')"></span>
                            </span>
                        </div>
                        
                        <h3 class="text-white font-bold text-base md:text-lg mb-1 uppercase italic tracking-tight" x-text="item.subject"></h3>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="w-1.5 h-1.5 rounded-full" 
                                  :class="item.status === 'sent' ? 'bg-yellow-500 animate-pulse' : (item.status === 'replied' ? 'bg-green-500' : 'bg-gray-500')">
                            </span>
                            <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest" 
                               :class="item.status === 'sent' ? 'text-yellow-500' : (item.status === 'replied' ? 'text-green-500' : 'text-gray-500')"
                               x-text="getStatusDescription(item.status)">
                            </p>
                        </div>

                        <p class="text-gray-400 text-xs md:text-sm line-clamp-2 md:line-clamp-1 italic opacity-70 leading-relaxed" x-text="item.message"></p>
                    </div>

                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between md:justify-between shrink-0 pt-4 md:pt-0 border-t md:border-t-0 border-white/5">
                        <p class="text-gray-500 md:text-white text-[10px] md:text-sm font-bold font-mono md:font-sans" x-text="item.date_formatted"></p>
                        
                        <button @click="selectedItem = item" 
                                type="button" 
                                class="text-[10px] font-black text-blue-500 hover:text-white transition-colors uppercase tracking-[0.2em] flex items-center gap-1 cursor-pointer">
                            Lihat <span class="hidden md:inline">Detail</span> <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

        {{-- EMPTY STATE --}}
        <div x-show="filteredItems.length === 0" class="text-center py-16 md:py-20 bg-[#0a0a0a] border border-dashed border-white/10 rounded-xl mx-2">
            <span class="material-symbols-outlined text-gray-600 text-4xl mb-2">inbox</span>
            <p class="text-gray-500 text-xs md:text-sm font-bold uppercase italic tracking-widest px-4">Belum ada riwayat pesan.</p>
        </div>
    </div>

    {{-- MODAL --}}
    <template x-teleport="body">
        <div x-show="selectedItem" 
             class="fixed inset-0 z-[9999] flex items-end md:items-center justify-center p-0 md:p-4"
             style="display: none;">
            
            <div x-show="selectedItem" 
                 x-transition.opacity.duration.300ms
                 @click="selectedItem = null"
                 class="absolute inset-0 bg-black/95 md:bg-black/90 backdrop-blur-sm"></div>

            <div x-show="selectedItem"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full md:translate-y-4 md:scale-95"
                 class="bg-[#0f0f0f] border-t md:border border-white/10 w-full max-w-2xl rounded-t-2xl md:rounded-2xl overflow-hidden shadow-2xl relative z-10 max-h-[90vh] flex flex-col">
                
                <template x-if="selectedItem">
                    <div class="flex flex-col h-full">
                        {{-- Modal Header --}}
                        <div class="p-5 md:p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                            <div>
                                <h4 class="text-blue-500 text-[9px] md:text-[10px] font-black tracking-[0.3em] uppercase">Detail Pesan</h4>
                                <p class="text-white font-bold italic uppercase tracking-tighter" x-text="'#PESAN-' + String(selectedItem.id).padStart(4, '0')"></p>
                            </div>
                            <button @click="selectedItem = null" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/5 text-gray-400 hover:text-white transition-colors">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        <div class="flex-1 overflow-y-auto p-5 md:p-8 space-y-6">
                            {{-- INFO STATUS --}}
                            <div class="p-4 rounded-lg border flex items-start gap-4"
                                 :class="selectedItem.status === 'sent' ? 'bg-yellow-500/5 border-yellow-500/20' : (selectedItem.status === 'replied' ? 'bg-green-500/5 border-green-500/20' : 'bg-gray-500/5 border-gray-500/20')">
                                
                                <span class="material-symbols-outlined text-2xl shrink-0"
                                      :class="selectedItem.status === 'sent' ? 'text-yellow-500' : (selectedItem.status === 'replied' ? 'text-green-500' : 'text-gray-500')"
                                      x-text="selectedItem.status === 'sent' ? 'hourglass_top' : (selectedItem.status === 'replied' ? 'mark_email_read' : 'lock')">
                                </span>
                                
                                <div>
                                    <h5 class="text-xs md:text-sm font-bold uppercase tracking-wide text-white" x-text="getStatusDescription(selectedItem.status)"></h5>
                                    <p class="text-[10px] md:text-xs text-gray-400 mt-1 leading-relaxed">
                                         <span x-show="selectedItem.status === 'sent'">Pesan Anda sedang ditinjau. Mohon tunggu balasan Admin melalui Email.</span>
                                        <span x-show="selectedItem.status === 'replied'">Admin telah membalas pesan ini. Silakan cek Inbox atau Folder Spam Email Anda.</span>
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-white/5 pb-6">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Status Terkini</p>
                                    <span :class="getStatusClass(selectedItem.status)" 
                                          class="px-2 py-1 text-[9px] font-black uppercase tracking-widest border rounded inline-block">
                                        <span x-text="getBadgeLabel(selectedItem.status)"></span>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Tanggal Kirim</p>
                                    <p class="text-white text-sm font-bold">
                                        <span x-text="selectedItem.date_formatted"></span>
                                        <span class="text-gray-500 text-xs ml-1" x-text="selectedItem.time_formatted"></span>
                                    </p>
                                </div>
                            </div>

                            <div>
                                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Isi Pesan Anda</p>
                                <div class="bg-white/5 border border-white/10 p-5 md:p-6 rounded-xl italic text-gray-300 text-sm md:text-base leading-relaxed whitespace-pre-wrap" x-text="selectedItem.message"></div>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="p-5 md:p-6 bg-white/5 border-t border-white/5 flex justify-end">
                            <button @click="selectedItem = null" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-500 text-white font-black text-[10px] uppercase tracking-[0.2em] transition-all clip-button">
                                Tutup Pesan
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .clip-button { clip-path: polygon(10% 0, 100% 0, 100% 70%, 90% 100%, 0 100%, 0% 30%); }
    
    /* Tambahkan animasi untuk modal mobile agar muncul dari bawah */
    @media (max-width: 768px) {
        .clip-button { clip-path: none; border-radius: 8px; }
    }
</style>

@endsection