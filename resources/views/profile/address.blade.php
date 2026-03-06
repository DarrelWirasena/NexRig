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

        <div class="flex items-center gap-3 shrink-0">
            @if(request('origin') === 'checkout')
            <a href="{{ route('checkout.index') }}"
                class="flex items-center gap-2 px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white rounded-lg font-bold transition-all border border-white/10 text-sm">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Back to Checkout</span>
            </a>
            @endif

            <a href="{{ route('address.create', request('origin') ? ['origin' => request('origin')] : []) }}"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-bold transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)] hover:shadow-[0_0_25px_rgba(37,99,235,0.5)] text-sm shrink-0">
                <span class="material-symbols-outlined text-sm">add_location_alt</span>
                <span>Add New Address</span>
            </a>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-400 rounded-xl text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">check_circle</span>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-sm">error</span>
        {{ session('error') }}
    </div>
    @endif

    {{-- INFO saat dari checkout --}}
    @if(request('origin') === 'checkout')
    <div class="mb-6 p-4 bg-blue-600/10 border border-blue-600/20 rounded-xl text-sm text-blue-300 flex items-center gap-3">
        <span class="material-symbols-outlined text-blue-400">info</span>
        Pilih alamat yang ingin digunakan untuk pengiriman, lalu klik <strong>Gunakan</strong>.
    </div>
    @endif

    {{-- STATS BAR --}}
    @if($addresses->count() > 0)
    @php $withCoords = $addresses->filter(fn($a) => $a->latitude && $a->longitude)->count(); @endphp
    <div class="flex items-center gap-3 mb-6 text-xs text-gray-600">
        <span>{{ $addresses->count() }} alamat tersimpan</span>
        <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
        <span class="flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
            {{ $withCoords }} dengan GPS
        </span>
        @if($withCoords < $addresses->count())
            <span class="w-1 h-1 bg-gray-700 rounded-full"></span>
            <span class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-gray-600 inline-block"></span>
                {{ $addresses->count() - $withCoords }} tanpa GPS
            </span>
            @endif
    </div>
    @endif

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

        {{-- CARD ADD NEW --}}
        <a href="{{ route('address.create', request('origin') ? ['origin' => request('origin')] : []) }}"
            class="group relative flex flex-col items-center justify-center p-8 rounded-xl border-2 border-dashed border-white/10
                  bg-transparent hover:bg-white/[0.02] hover:border-blue-600/40 transition-all min-h-[260px] cursor-pointer">
            <div class="w-14 h-14 rounded-full bg-blue-600/10 border border-blue-600/20 flex items-center justify-center mb-4
                        group-hover:scale-110 group-hover:bg-blue-600 group-hover:border-blue-500 transition-all duration-300">
                <span class="material-symbols-outlined text-blue-500 group-hover:text-white text-2xl transition-colors">add_location_alt</span>
            </div>
            <span class="text-blue-500/70 group-hover:text-blue-400 font-bold tracking-widest uppercase text-xs transition-colors">
                Add New Address
            </span>
        </a>

        {{-- LOOP DATA --}}
        @forelse($addresses as $address)
        <div class="relative">
            <x-address-card
                :id="$address->id"
                :type="$address->label"
                :icon="$address->label === 'Office' ? 'business' : ($address->label === 'Home' ? 'home' : 'place')"
                :recipient="$address->recipient_name"
                :address="$address->full_address"
                :phone="$address->phone"
                :isDefault="$address->is_default"
                :showUseButton="request('origin') === 'checkout'"
                :village="$address->village ?? null"
                :district="$address->district ?? null"
                :city="$address->city ?? null"
                :province="$address->province ?? null"
                :postalCode="$address->postal_code ?? null"
                :hasCoords="(bool)($address->latitude && $address->longitude)" />

            {{-- Form delete (dipanggil via JS confirmDelete) --}}
            <form id="delete-form-{{ $address->id }}"
                action="{{ route('address.destroy', $address->id) }}"
                method="POST"
                class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
        @empty
        <div class="col-span-full py-24 text-center border-2 border-dashed border-white/5 rounded-xl">
            <span class="material-symbols-outlined text-5xl text-gray-700 mb-4 block">location_off</span>
            <p class="text-gray-500 font-bold mb-1">Belum ada alamat tersimpan</p>
            <p class="text-gray-600 text-sm">Tambahkan alamat pengiriman untuk mempercepat checkout.</p>
        </div>
        @endforelse

    </div>
</div>

@endsection