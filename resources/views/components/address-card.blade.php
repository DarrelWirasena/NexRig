@props([
'id' => null,
'type' => 'Home',
'icon' => 'home',
'recipient',
'address',
'phone',
'isDefault' => false,
'showUseButton' => false,
// Wilayah baru
'village' => null,
'district' => null,
'city' => null,
'province' => null,
'postalCode' => null,
// Koordinat
'hasCoords' => false,
])

<div {{ $attributes->merge(['class' => 
    'relative group bg-[#0a0a0a] border rounded-xl overflow-hidden transition-all duration-300 flex flex-col h-full ' . 
    ($isDefault 
        ? 'border-blue-600/50 shadow-[0_0_25px_rgba(37,99,235,0.12)]' 
        : 'border-white/10 hover:border-white/25')
]) }}>

    {{-- Top accent line --}}
    <div class="absolute top-0 left-0 right-0 h-[2px] {{ $isDefault ? 'bg-gradient-to-r from-blue-600 via-blue-400 to-blue-600' : 'bg-white/5 group-hover:bg-white/10' }} transition-all duration-300"></div>

    {{-- Garis kiri biru jika default --}}
    @if($isDefault)
    <div class="absolute top-0 left-0 w-[3px] h-full bg-gradient-to-b from-blue-500 to-blue-700 rounded-l-xl"></div>
    @endif

    {{-- ── BODY ── --}}
    <div class="p-6 flex-1 {{ $isDefault ? 'pl-7' : '' }}">

        {{-- Header --}}
        <div class="flex items-start justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center {{ $isDefault ? 'bg-blue-600/20 text-blue-400' : 'bg-white/5 text-gray-400 group-hover:bg-white/10 group-hover:text-white' }} transition-all">
                    <span class="material-symbols-outlined text-lg">{{ $icon }}</span>
                </div>
                <div>
                    <h3 class="font-bold text-sm {{ $isDefault ? 'text-white' : 'text-gray-300 group-hover:text-white' }} transition-colors uppercase tracking-wider">
                        {{ $type }}
                    </h3>
                    {{-- Koordinat badge --}}
                    @if($hasCoords)
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-green-400 uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                        GPS Ready
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-gray-600 uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-600 inline-block"></span>
                        No GPS
                    </span>
                    @endif
                </div>
            </div>

            @if($isDefault)
            <span class="px-2.5 py-1 bg-blue-600/20 text-blue-400 text-[9px] font-black uppercase rounded-full tracking-widest border border-blue-600/30">
                ✦ DEFAULT
            </span>
            @endif
        </div>

        {{-- Detail --}}
        <div class="space-y-3.5">

            {{-- Recipient --}}
            <div>
                <span class="text-[9px] text-gray-600 uppercase font-bold tracking-widest">Penerima</span>
                <p class="text-white font-semibold text-sm mt-0.5">{{ $recipient }}</p>
                <p class="text-gray-500 text-xs font-mono">{{ $phone }}</p>
            </div>

            {{-- Alamat --}}
            <div>
                <span class="text-[9px] text-gray-600 uppercase font-bold tracking-widest">Alamat</span>
                <p class="text-gray-300 text-xs leading-relaxed mt-0.5">{!! nl2br(e($address)) !!}</p>
            </div>

            {{-- Wilayah — tampil jika ada data baru --}}
            @if($village || $district || $city || $province)
            <div class="pt-1">
                <span class="text-[9px] text-gray-600 uppercase font-bold tracking-widest">Wilayah</span>
                <div class="flex flex-wrap gap-1.5 mt-1.5">
                    @foreach(array_filter([$village, $district, $city, $province]) as $region)
                    <span class="px-2 py-0.5 bg-white/5 border border-white/10 rounded text-[10px] text-gray-400 font-medium">
                        {{ $region }}
                    </span>
                    @endforeach
                    @if($postalCode)
                    <span class="px-2 py-0.5 bg-blue-600/10 border border-blue-600/20 rounded text-[10px] text-blue-400 font-bold font-mono">
                        {{ $postalCode }}
                    </span>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ── FOOTER ACTIONS ── --}}
    <div class="p-4 border-t {{ $isDefault ? 'border-blue-600/10 bg-blue-600/5' : 'border-white/5 bg-black/20' }} flex items-center justify-between gap-2">

        {{-- Edit & Delete --}}
        <div class="flex gap-1">
            <a href="{{ $id ? route('address.edit', array_filter(['id' => $id, 'origin' => request('origin')])) : '#' }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all"
                title="Edit">
                <span class="material-symbols-outlined text-[18px]">edit</span>
            </a>

            @if($id)
            <button type="button"
                onclick="confirmDelete('{{ $id }}')"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all"
                title="Delete">
                <span class="material-symbols-outlined text-[18px]">delete</span>
            </button>
            @endif
        </div>

        {{-- Default / Use button --}}
        <div class="flex-1 flex justify-end">
            @if($showUseButton)
            <form action="{{ route('address.set_default', $id) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="redirect_to" value="checkout">
                @if($isDefault)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-green-400 border border-green-500/20 rounded-lg bg-green-500/5">
                    <span class="material-symbols-outlined text-sm">check_circle</span>
                    Selected
                </span>
                @else
                <button type="submit"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[10px] font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-all">
                    <span class="material-symbols-outlined text-sm">check</span>
                    Gunakan
                </button>
                @endif
            </form>
            @else
            @if($isDefault)
            <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">
                Default Address
            </span>
            @else
            <form action="{{ route('address.set_default', $id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="text-[10px] font-bold text-gray-600 hover:text-white uppercase tracking-widest transition-colors">
                    Set as Default
                </button>
            </form>
            @endif
            @endif
        </div>
    </div>

</div>