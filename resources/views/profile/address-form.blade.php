@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto pb-20">
    
    {{-- HEADER --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('address.index') }}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all group">
                <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                    {{ isset($address) ? 'Edit' : 'New' }} <span class="text-blue-600">Destination</span>
                </h1>
                <p class="text-gray-400 text-sm mt-2">
                    {{ isset($address) ? 'Update delivery details for this location.' : 'Add a new secure shipping address to your book.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 text-red-500 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CONTAINER --}}
    <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-10 relative overflow-hidden transition-all hover:border-blue-600/30">
        
        <form action="{{ isset($address) ? route('address.update', $address->id) : route('address.store') }}" method="POST">
            @csrf
            @if(isset($address)) @method('PUT') @endif

            {{-- 1. MEMANGGIL KOMPONEN FIELD (Nama, HP, Alamat, dll) --}}
            <x-address-form-fields :address="$address ?? null" />

            {{-- 2. TOMBOL "SET AS DEFAULT" (Checkbox Modern) --}}
            <div class="col-span-1 md:col-span-2 mb-8">
                <label class="flex items-center gap-3 cursor-pointer group w-max select-none">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="is_default" value="1" class="peer sr-only"
                            {{ (old('is_default', $address->is_default ?? false)) ? 'checked' : '' }}>
                        
                        <div class="w-5 h-5 border-2 border-white/20 rounded bg-white/5 
                                    peer-checked:bg-blue-600 peer-checked:border-blue-500 
                                    transition-all duration-200 flex items-center justify-center 
                                    group-hover:border-blue-400">
                            <span class="material-symbols-outlined text-white text-[16px] font-black 
                                        scale-0 peer-checked:scale-100 transition-transform duration-200">
                                check
                            </span>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-gray-400 group-hover:text-white transition-colors">
                        Atur sebagai Alamat Utama
                    </span>
                </label>
            </div>

            {{-- 3. ACTION BUTTONS (Save & Cancel) --}}
            <div class="flex justify-end gap-4 pt-6 border-t border-white/10">
                <a href="{{ route('address.index') }}" class="px-6 py-3 rounded-lg text-sm font-bold text-gray-500 hover:text-white hover:bg-white/5 transition-all uppercase tracking-widest">
                    CANCEL
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold uppercase tracking-widest transition-all rounded-lg shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    {{ isset($address) ? 'Update Address' : 'Save Address' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection