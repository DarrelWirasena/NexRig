@props([
    'id' => null,           // [BARU] ID Alamat untuk link edit/delete
    'type' => 'Home',       
    'icon' => 'home',       
    'recipient',            
    'address',              
    'phone',                
    'isDefault' => false,  
    'showUseButton' => false,   
])

<div {{ $attributes->merge(['class' => 'relative group bg-[#0a0a0a] border rounded-xl overflow-hidden transition-all flex flex-col h-full ' . ($isDefault ? 'border-blue-600/50 shadow-[0_0_20px_rgba(37,99,235,0.15)]' : 'border-white/10 hover:border-blue-600/30')]) }}>
    
    {{-- Garis Biru Kiri (Hanya jika Active) --}}
    @if($isDefault)
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
    @endif
    
    <div class="p-6 pb-4 flex-1">
        {{-- Header Kartu --}}
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-2 {{ $isDefault ? 'text-white' : 'text-gray-400 group-hover:text-white' }} transition-colors">
                <span class="material-symbols-outlined {{ $isDefault ? 'text-blue-600' : '' }}">{{ $icon }}</span>
                <h3 class="font-bold text-lg">{{ $type }}</h3>
            </div>
            
            @if($isDefault)
                <span class="px-2 py-0.5 bg-blue-600/20 text-blue-500 text-[10px] font-black uppercase rounded tracking-widest border border-blue-600/30">DEFAULT</span>
            @endif
        </div>

        {{-- Detail Alamat --}}
        <div class="space-y-4">
            <div class="flex flex-col">
                <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">Recipient</span>
                <p class="text-white font-medium">{{ $recipient }}</p>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">Location</span>
                <p class="text-gray-300 text-sm leading-relaxed">{!! nl2br(e($address)) !!}</p>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">Phone</span>
                <p class="text-gray-300 text-sm font-mono">{{ $phone }}</p>
            </div>
        </div>
    </div>
    {{-- Footer Actions --}}
    <div class="mt-auto p-4 bg-[#050014]/50 border-t border-white/5 flex items-center justify-between">
        <div class="flex gap-2">
            {{-- TOMBOL EDIT --}}
            <a href="{{ $id ? route('address.edit', array_filter(['id' => $id, 'origin' => request('origin')])) : '#' }}" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded transition-colors" title="Edit">
                <span class="material-symbols-outlined text-xl">edit</span>
            </a>

            {{-- TOMBOL DELETE (Dihubungkan ke SweetAlert2) --}}
            @if($id)
                {{-- Kita tidak perlu form di sini karena form sudah ada di file address.blade.php --}}
                {{-- Tombol ini hanya memicu fungsi JavaScript global --}}
                <button type="button" 
                        onclick="confirmDelete('{{ $id }}')" 
                        class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-colors" title="Delete">
                    <span class="material-symbols-outlined text-xl">delete</span>
                </button>
            @else
                {{-- Tampilan Statis --}}
                <button class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded transition-colors" title="Delete">
                    <span class="material-symbols-outlined text-xl">delete</span>
                </button>
            @endif
        </div>

        @if($isDefault)
            <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest pointer-events-none">
                Default Address
            </span>
        @else
            <form action="{{ route('address.set_default', $id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-[10px] font-bold text-gray-500 hover:text-white uppercase tracking-widest transition-colors">
                    Set as Default
                </button>
            </form>
        @endif
        @if($showUseButton)
            <form action="{{ route('address.set_default', $id) }}" method="POST" class="mt-4">
                @csrf
                @method('PATCH')
                <input type="hidden" name="redirect_to" value="checkout">
                @if($isDefault)
                    <span class="w-full flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-green-500 border border-green-500/20 rounded-lg bg-green-500/5">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        Currently Selected
                    </span>
                @else
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-all">
                        <span class="material-symbols-outlined text-sm">check</span>
                        Use This Address
                    </button>
                @endif
            </form>
        @endif
    </div>
    
</div>
