@extends('layouts.dashboard')

@section('content')

<div class="max-w-6xl mx-auto pb-20">
    
    {{-- HEADER HALAMAN --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                Address <span class="text-blue-600">Book</span>
            </h1>
            <p class="text-gray-400 text-sm mt-2">Manage your shipping destinations for faster checkout.</p>
        </div>
        
        {{-- [UPDATE] Link ke Route Create --}}
        <a href="{{ route('address.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold transition-all shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] shrink-0">
            <span class="material-symbols-outlined">add</span>
            <span>ADD NEW ADDRESS</span>
        </a>
    </div>

    {{-- ADDRESS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- 1. ADD NEW PLACEHOLDER (Link ke Route Create) --}}
        <a href="{{ route('address.create') }}" class="group relative flex flex-col items-center justify-center p-8 rounded-xl border-2 border-dashed border-white/10 bg-white/5 hover:bg-white/10 hover:border-blue-600/50 transition-all min-h-[280px] cursor-pointer">
            <div class="w-16 h-16 rounded-full bg-blue-600/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform group-hover:bg-blue-600 group-hover:text-white">
                <span class="material-symbols-outlined text-blue-500 group-hover:text-white text-3xl transition-colors">add_location_alt</span>
            </div>
            <span class="text-blue-500 font-bold tracking-wider uppercase text-sm group-hover:text-white transition-colors">Add New Destination</span>
            <span class="text-gray-500 text-xs mt-1">Shipping or Billing</span>
        </a>

        {{-- 2. HOME ADDRESS (Kasih ID Dummy '1') --}}
        <x-address-card 
            id="1" 
            type="Home (Primary)" 
            icon="home"
            :recipient="auth()->user()->name"
            address="Jl. Sudirman Kav 52-53&#10;Jakarta Selatan, 12190&#10;Indonesia"
            phone="+62 812-3456-7890"
            :isDefault="true" 
            :isActive="true" 
        />

        {{-- 3. OFFICE ADDRESS (Kasih ID Dummy '2') --}}
        <x-address-card 
            id="2"
            type="Office" 
            icon="business"
            recipient="NexRig HQ Receiver"
            address="Gedung Cyber 2, Lt. 15&#10;Kuningan, Jakarta&#10;Indonesia"
            phone="+62 21-5555-1234"
        />

    </div>

</div>

@endsection