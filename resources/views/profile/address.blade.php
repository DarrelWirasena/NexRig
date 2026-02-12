@extends('layouts.dashboard')

@section('content')

<div class="max-w-6xl mx-auto pb-20">
    
    {{-- HEADER --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                Address <span class="text-blue-600">Book</span>
            </h1>
            <p class="text-gray-400 text-sm mt-2">Manage your shipping destinations for faster checkout.</p>
        </div>
        
        <a href="{{ route('address.create') }}" class="flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold transition-all shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] shrink-0">
            <span class="material-symbols-outlined">add</span>
            <span>ADD NEW ADDRESS</span>
        </a>
    </div>

    {{-- ALERT (Tetap ada sebagai cadangan jika JS gagal) --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 text-green-500 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 text-red-500 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- CARD ADD NEW --}}
        <a href="{{ route('address.create') }}" class="group relative flex flex-col items-center justify-center p-8 rounded-xl border-2 border-dashed border-white/10 bg-white/5 hover:bg-white/10 hover:border-blue-600/50 transition-all min-h-[280px] cursor-pointer">
            <div class="w-16 h-16 rounded-full bg-blue-600/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform group-hover:bg-blue-600 group-hover:text-white">
                <span class="material-symbols-outlined text-blue-500 group-hover:text-white text-3xl transition-colors">add_location_alt</span>
            </div>
            <span class="text-blue-500 font-bold tracking-wider uppercase text-sm group-hover:text-white transition-colors">Add New Destination</span>
        </a>

        {{-- LOOP DATA --}}
        @forelse($addresses as $address)
            <div class="relative group">
                {{-- Panggil Component Card --}}
                <x-address-card 
                    :id="$address->id" 
                    :type="$address->label" 
                    :icon="$address->label == 'Office' ? 'business' : ($address->label == 'Home' ? 'home' : 'place')"
                    :recipient="$address->recipient_name"
                    :address="$address->full_address . '&#10;' . $address->city . ', ' . $address->postal_code"
                    :phone="$address->phone"
                    :isDefault="$address->is_default" 
                    :isActive="true" 
                />

                {{-- FORM HIDDEN UNTUK DELETE --}}
                <form id="delete-form-{{ $address->id }}" 
                      action="{{ route('address.destroy', $address->id) }}" 
                      method="POST" 
                      class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @empty
            {{-- TAMPILAN JIKA TIDAK ADA DATA --}}
            <div class="col-span-full py-20 text-center border-2 border-dashed border-white/5 rounded-xl">
                <p class="text-gray-500">No addresses found in your book.</p>
            </div>
        @endforelse

    </div>
</div>
@endsection