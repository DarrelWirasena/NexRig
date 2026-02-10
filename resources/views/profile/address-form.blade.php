@extends('layouts.dashboard')

@section('content')

<div class="max-w-4xl mx-auto pb-20">
    
    {{-- HEADER HALAMAN --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('profile.address') }}" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all group">
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

    {{-- FORM CONTAINER --}}
    <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-10 relative overflow-hidden transition-all hover:border-blue-600/30">
        
        {{-- LOGIC FORM ACTION: Update jika ada data, Store jika baru --}}
        <form action="{{ isset($address) ? route('address.update', $address->id) : route('address.store') }}" method="POST">
            @csrf
            
            {{-- Jika Edit, tambahkan method PUT --}}
            @if(isset($address))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                {{-- 1. LABEL ALAMAT --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Address Label</label>
                    <div class="flex gap-4">
                        {{-- Radio Button Style Tech --}}
                        @foreach(['Home', 'Office', 'Studio', 'Other'] as $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="label" value="{{ $label }}" class="peer sr-only" 
                                    {{ (old('label', $address->label ?? 'Home') == $label) ? 'checked' : '' }}>
                                <div class="px-6 py-2 rounded-lg border border-white/10 bg-white/5 text-gray-400 text-sm font-bold uppercase transition-all peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-500 hover:bg-white/10">
                                    {{ $label }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- 2. RECIPIENT NAME --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Recipient Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="text-gray-500 material-symbols-outlined text-lg">person</span>
                        </div>
                        <input type="text" name="recipient_name" 
                               value="{{ old('recipient_name', $address->recipient_name ?? '') }}"
                               placeholder="e.g. Alex Rivers"
                               class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 bg-white/5 border border-white/10 text-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                    </div>
                </div>

                {{-- 3. PHONE NUMBER --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="text-gray-500 material-symbols-outlined text-lg">call</span>
                        </div>
                        <input type="text" name="phone" 
                               value="{{ old('phone', $address->phone ?? '') }}"
                               placeholder="+62 ..."
                               class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 bg-white/5 border border-white/10 text-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                    </div>
                </div>

                {{-- 4. CITY --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">City</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="text-gray-500 material-symbols-outlined text-lg">location_city</span>
                        </div>
                        <input type="text" name="city" 
                               value="{{ old('city', $address->city ?? '') }}"
                               placeholder="e.g. Jakarta Selatan"
                               class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 bg-white/5 border border-white/10 text-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                    </div>
                </div>

                {{-- 5. POSTAL CODE --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Postal Code</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                            <span class="text-gray-500 material-symbols-outlined text-lg">markunread_mailbox</span>
                        </div>
                        <input type="text" name="postal_code" 
                               value="{{ old('postal_code', $address->postal_code ?? '') }}"
                               placeholder="e.g. 12xxx"
                               class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 bg-white/5 border border-white/10 text-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
                    </div>
                </div>

                {{-- 6. FULL ADDRESS --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Address</label>
                    <div class="relative">
                        <div class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none">
                            <span class="text-gray-500 material-symbols-outlined text-lg">home_pin</span>
                        </div>
                        <textarea name="full_address" rows="3"
                                  placeholder="Street name, Building, Floor, Unit no..."
                                  class="w-full pl-12 pr-4 py-3 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 bg-white/5 border border-white/10 text-white focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">{{ old('full_address', $address->full_address ?? '') }}</textarea>
                    </div>
                </div>

                {{-- 7. SET AS DEFAULT (Checkbox Custom) --}}
                <div class="col-span-1 md:col-span-2">
                    <label class="flex items-center gap-3 cursor-pointer group w-max">
                        <div class="relative">
                            <input type="checkbox" name="is_default" value="1" class="peer sr-only"
                                {{ (old('is_default', $address->is_default ?? false)) ? 'checked' : '' }}>
                            
                            {{-- Custom Checkbox Box --}}
                            <div class="w-10 h-6 bg-white/10 rounded-full peer-checked:bg-blue-600 transition-all"></div>
                            <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full transition-all peer-checked:translate-x-4"></div>
                        </div>
                        <span class="text-sm font-bold text-gray-400 group-hover:text-white transition-colors">Set as Default Address</span>
                    </label>
                </div>

            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-4 pt-6 border-t border-white/10">
                <a href="{{ route('profile.address') }}" class="px-6 py-3 rounded-lg text-sm font-bold text-gray-500 hover:text-white hover:bg-white/5 transition-all">
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