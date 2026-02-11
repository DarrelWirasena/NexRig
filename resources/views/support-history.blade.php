@extends('layouts.dashboard')

@section('content')

{{-- Container Utama: Kita set h-screen agar tinggi pas dengan layar --}}
<div class="max-w-5xl mx-auto flex flex-col h-[calc(100vh-120px)]" x-data="{ openModal: null }">
    
    {{-- HEADER HALAMAN: Tetap di tempat (Sticky) --}}
    <div class="shrink-0 bg-[#050505] z-10 pb-6 border-b border-white/10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter leading-none">
                    Support <span class="text-blue-600">History</span>
                </h1>
                <p class="text-gray-400 text-sm mt-2">Arsip komunikasi dan tiket bantuan teknis Anda.</p>
            </div>
            
            {{-- SEARCH BAR --}}
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <span class="material-symbols-outlined text-gray-500 text-[20px]">search</span>
                </div>
                <input type="text" placeholder="Search Subject..." 
                       class="w-full pl-10 pr-4 py-2 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 transition-all focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    {{-- LIST PESAN: Bagian ini yang BISA DI-SCROLL --}}
    <div class="flex-1 overflow-y-auto pr-4 mt-8 space-y-6 custom-scrollbar no-bounce">
        @forelse($history as $item)
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 hover:border-blue-600/50 transition-all group">
                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="px-2 py-0.5 bg-blue-600/10 text-blue-500 text-[10px] font-black uppercase tracking-widest border border-blue-600/20">
                                {{ $item->subject }}
                            </span>
                            <span class="text-gray-600 text-[10px] font-mono uppercase tracking-widest">
                                #SUP-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <h3 class="text-white font-bold text-lg mb-2 truncate max-w-md">"{{ $item->message }}"</h3>
                    </div>

                    <div class="flex flex-col md:items-end justify-between shrink-0">
                        <p class="text-white text-sm font-bold">{{ $item->created_at->format('d M Y') }}</p>
                        <button @click="openModal = {{ $item->id }}" 
                                class="mt-4 text-xs font-bold text-blue-500 hover:text-white transition-colors uppercase tracking-widest flex items-center gap-1">
                            View Details <span class="material-symbols-outlined text-sm">open_in_new</span>
                        </button>
                    </div>
                </div>

                {{-- MODAL OVERLAY (Sama seperti sebelumnya) --}}
               {{-- MODAL OVERLAY --}}
<template x-teleport="body">
    {{-- Background Overlay (Gelap transparan) --}}
    <div x-show="openModal === {{ $item->id }}" 
         {{-- Animasi Backdrop --}}
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
         style="display: none;">
        
        {{-- Container Modal --}}
        <div @click.away="openModal = null" 
             {{-- Animasi Konten (Muncul dari bawah + Membesar sedikit) --}}
             x-show="openModal === {{ $item->id }}"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="bg-[#0f0f0f] border border-white/10 w-full max-w-2xl rounded-2xl overflow-hidden shadow-[0_0_50px_rgba(0,0,0,0.8)]">
            
            {{-- Modal Header --}}
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <div>
                    <h4 class="text-blue-500 text-[10px] font-black tracking-[0.3em] uppercase">Message Details</h4>
                    <p class="text-white font-bold italic uppercase tracking-tighter">Support Ticket #SUP-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <button @click="openModal = null" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/10 text-gray-500 hover:text-white transition-all">
                    <span class="material-symbols-outlined text-xl">close</span>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-8 border-b border-white/5 pb-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Subject</p>
                        <p class="text-white font-bold">{{ ucfirst($item->subject) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Date Sent</p>
                        <p class="text-white font-bold">{{ $item->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Your Message</p>
                    <div class="bg-white/5 border border-white/10 p-6 rounded-xl italic text-gray-300 leading-relaxed shadow-inner">
                        "{{ $item->message }}"
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="p-6 bg-white/5 border-t border-white/5 flex justify-end">
                <button @click="openModal = null" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-black text-[10px] uppercase tracking-widest transition-all clip-button shadow-[0_0_20px_rgba(37,99,235,0.3)]">
                    Close Details
                </button>
            </div>
        </div>
    </div>
</template>
            </div>
        @empty
            <div class="text-center py-20 bg-[#0a0a0a] border border-dashed border-white/10 rounded-xl">
                <p class="text-gray-500">No support history found.</p>
            </div>

            
        @endforelse

        {{-- Spasi bawah agar scroll tidak mepet --}}
        <div class="pb-10"></div>
    </div>

</div>

{{-- CSS UNTUK SCROLLBAR CANTIK --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #1e1e1e;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }
</style>



@endsection