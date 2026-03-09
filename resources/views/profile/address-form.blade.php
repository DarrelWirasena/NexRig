@extends('layouts.dashboard')

@section('content')

<style>
    /* ── Custom Dropdown ── */
    .custom-select {
        position: relative;
    }

    .custom-select .dd-trigger {
        text-align: left;
    }

    .custom-select.open .dd-trigger {
        border-color: rgb(59 130 246 / 0.6) !important;
        background: rgb(255 255 255 / 0.07) !important;
        opacity: 1 !important;
    }

    .custom-select.open .dd-trigger .material-symbols-outlined {
        transform: rotate(180deg);
    }

    .custom-select .dd-menu {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
    }

    .custom-select .dd-list li {
        padding: 9px 14px;
        font-size: 13px;
        color: #d1d5db;
        cursor: pointer;
        transition: background 0.12s;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .custom-select .dd-list li:hover {
        background: rgb(37 99 235 / 0.15);
        color: #fff;
    }

    .custom-select .dd-list li.active {
        background: rgb(37 99 235 / 0.25);
        color: #60a5fa;
        font-weight: 700;
    }

    .custom-select .dd-list::-webkit-scrollbar {
        width: 4px;
    }

    .custom-select .dd-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-select .dd-list::-webkit-scrollbar-thumb {
        background: #374151;
        border-radius: 4px;
    }

    .custom-select.disabled .dd-trigger {
        cursor: not-allowed !important;
        opacity: 0.4 !important;
    }
</style>

<div class="max-w-4xl mx-auto pb-20">

    {{-- HEADER --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('address.index', request('origin') ? ['origin' => request('origin')] : []) }}"
                class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:bg-blue-600 hover:text-white transition-all group">
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

    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-400 rounded-xl text-sm space-y-1">
        @foreach ($errors->all() as $error)
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">error</span> {{ $error }}
        </div>
        @endforeach
    </div>
    @endif

    {{-- FORM --}}
    <form action="{{ isset($address) ? route('address.update', $address->id) : route('address.store') }}"
        method="POST" id="addressForm">
        @csrf
        @if(isset($address)) @method('PUT') @endif
        @if(request('origin'))
        <input type="hidden" name="origin" value="{{ request('origin') }}">
        @endif

        {{-- Hidden koordinat (diisi otomatis via geocoding) --}}
        <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude',  $address->latitude  ?? '') }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $address->longitude ?? '') }}">

        <div class="space-y-6">

            {{-- ═══════════════════════════════════════
                 SECTION 1 — INFORMASI PENERIMA
            ═══════════════════════════════════════ --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-8 hover:border-white/20 transition-colors">
                <h2 class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">person</span>
                    Informasi Penerima
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Label --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Tipe Alamat <span class="text-red-500">*</span>
                        </label>
                        <div class="flex gap-3 flex-wrap">
                            @foreach(['Home', 'Office', 'Other'] as $lbl)
                            <label class="cursor-pointer">
                                <input type="radio" name="label" value="{{ $lbl }}" class="peer sr-only"
                                    {{ old('label', $address->label ?? 'Home') === $lbl ? 'checked' : '' }}>
                                <div class="px-5 py-2.5 rounded-lg border-2 border-white/10 text-gray-400 text-sm font-bold
                                                peer-checked:border-blue-500 peer-checked:bg-blue-600/10 peer-checked:text-blue-400
                                                hover:border-white/30 transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm">
                                        {{ $lbl === 'Home' ? 'home' : ($lbl === 'Office' ? 'business' : 'place') }}
                                    </span>
                                    {{ $lbl }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Nama Penerima --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Nama Penerima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="recipient_name"
                            value="{{ old('recipient_name', $address->recipient_name ?? '') }}"
                            placeholder="Nama lengkap penerima"
                            class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white text-sm
                                      placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:bg-white/[0.07] transition-all">
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">+62</span>
                            <input type="text" name="phone"
                                value="{{ old('phone', $address->phone ?? '') }}"
                                placeholder="812 3456 7890"
                                class="w-full bg-white/5 border border-white/10 rounded-lg pl-12 pr-4 py-3 text-white text-sm
                                          placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:bg-white/[0.07] transition-all">
                        </div>
                    </div>

                </div>
            </div>

            {{-- ═══════════════════════════════════════
                 SECTION 2 — WILAYAH (CASCADE DROPDOWN)
            ═══════════════════════════════════════ --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-8 hover:border-white/20 transition-colors">
                <h2 class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">map</span>
                    Wilayah Pengiriman
                </h2>

                {{-- Loading indicator --}}
                <div id="regionLoading" class="hidden mb-4 flex items-center gap-2 text-xs text-blue-400">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Memuat data wilayah...
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- PROVINSI --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select" id="dd_province">
                            <button type="button" class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-500 hover:border-blue-500/50 transition-all">
                                <span class="dd-label truncate">— Pilih Provinsi —</span>
                                <span class="material-symbols-outlined text-gray-500 text-sm shrink-0 transition-transform duration-200">expand_more</span>
                            </button>
                            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl overflow-hidden">
                                <div class="p-2 border-b border-white/10">
                                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white placeholder-gray-600 outline-none" placeholder="Cari provinsi...">
                                </div>
                                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
                            </div>
                        </div>
                        <input type="hidden" id="sel_province" name="province" value="">
                        <input type="hidden" name="province_name" id="province_name" value="{{ old('province', $address->province ?? '') }}">
                    </div>

                    {{-- KOTA/KABUPATEN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Kota / Kabupaten <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select disabled" id="dd_city">
                            <button type="button" disabled class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-600 cursor-not-allowed opacity-50 transition-all">
                                <span class="dd-label truncate">— Pilih Kota —</span>
                                <span class="material-symbols-outlined text-gray-600 text-sm shrink-0">expand_more</span>
                            </button>
                            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl overflow-hidden">
                                <div class="p-2 border-b border-white/10">
                                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white placeholder-gray-600 outline-none" placeholder="Cari kota...">
                                </div>
                                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
                            </div>
                        </div>
                        <input type="hidden" id="sel_city" name="city" value="">
                        <input type="hidden" name="city_name" id="city_name" value="{{ old('city', $address->city ?? '') }}">
                    </div>

                    {{-- KECAMATAN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select disabled" id="dd_district">
                            <button type="button" disabled class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-600 cursor-not-allowed opacity-50 transition-all">
                                <span class="dd-label truncate">— Pilih Kecamatan —</span>
                                <span class="material-symbols-outlined text-gray-600 text-sm shrink-0">expand_more</span>
                            </button>
                            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl overflow-hidden">
                                <div class="p-2 border-b border-white/10">
                                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white placeholder-gray-600 outline-none" placeholder="Cari kecamatan...">
                                </div>
                                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
                            </div>
                        </div>
                        <input type="hidden" id="sel_district" name="district" value="">
                        <input type="hidden" name="district_name" id="district_name" value="{{ old('district', $address->district ?? '') }}">
                    </div>

                    {{-- KELURAHAN --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Kelurahan / Desa <span class="text-red-500">*</span>
                        </label>
                        <div class="custom-select disabled" id="dd_village">
                            <button type="button" disabled class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-600 cursor-not-allowed opacity-50 transition-all">
                                <span class="dd-label truncate">— Pilih Kelurahan —</span>
                                <span class="material-symbols-outlined text-gray-600 text-sm shrink-0">expand_more</span>
                            </button>
                            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl overflow-hidden">
                                <div class="p-2 border-b border-white/10">
                                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white placeholder-gray-600 outline-none" placeholder="Cari kelurahan...">
                                </div>
                                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
                            </div>
                        </div>
                        <input type="hidden" id="sel_village" name="village" value="">
                        <input type="hidden" name="village_name" id="village_name" value="{{ old('village', $address->village ?? '') }}">
                    </div>

                    {{-- KODE POS (auto-fill) --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Kode Pos
                        </label>
                        <div class="relative">
                            <input type="text" name="postal_code" id="postal_code"
                                value="{{ old('postal_code', $address->postal_code ?? '') }}"
                                placeholder="Otomatis terisi, atau ketik manual"
                                class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white text-sm
                                          placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:bg-white/[0.07] transition-all">
                            {{-- Status geocoding --}}
                            <div id="geocodeStatus" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                                <svg id="geocodeSpinner" class="animate-spin w-4 h-4 text-blue-400 hidden" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <span id="geocodeDone" class="material-symbols-outlined text-green-400 text-sm hidden">check_circle</span>
                                <span id="geocodeFail" class="material-symbols-outlined text-red-400 text-sm hidden">error</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-600 mt-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">info</span>
                            Terisi otomatis dari peta — bisa diubah manual jika tidak sesuai
                        </p>
                    </div>

                </div>
            </div>

            {{-- ═══════════════════════════════════════
                 SECTION 3 — DETAIL ALAMAT LENGKAP
            ═══════════════════════════════════════ --}}
            <div class="bg-[#0a0a0a] border border-white/10 rounded-xl p-6 md:p-8 hover:border-white/20 transition-colors">
                <h2 class="text-xs font-bold uppercase tracking-widest text-blue-500 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">edit_location</span>
                    Detail Alamat
                </h2>

                <div class="space-y-5">
                    {{-- Alamat Lengkap --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="full_address" rows="3"
                            placeholder="Nama jalan, nomor rumah, RT/RW, patokan, dll."
                            class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white text-sm
                                         placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:bg-white/[0.07] transition-all resize-none">{{ old('full_address', $address->full_address ?? '') }}</textarea>
                    </div>

                    {{-- Set as Default --}}
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer group w-max select-none">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" name="is_default" value="1" class="peer sr-only"
                                    {{ old('is_default', $address->is_default ?? false) ? 'checked' : '' }}>
                                <div class="w-5 h-5 border-2 border-white/20 rounded bg-white/5
                                            peer-checked:bg-blue-600 peer-checked:border-blue-500
                                            transition-all duration-200 flex items-center justify-center
                                            group-hover:border-blue-400">
                                    <span class="material-symbols-outlined text-white text-[16px] font-black
                                                scale-0 peer-checked:scale-100 transition-transform duration-200">check</span>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-gray-400 group-hover:text-white transition-colors">
                                Atur sebagai Alamat Utama
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex justify-end gap-4 pt-2">
                <a href="{{ route('address.index', request('origin') ? ['origin' => request('origin')] : []) }}"
                    class="px-6 py-3 rounded-lg text-sm font-bold text-gray-500 hover:text-white hover:bg-white/5 transition-all uppercase tracking-widest">
                    CANCEL
                </a>
                <button type="submit"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold uppercase tracking-widest transition-all rounded-lg
                               shadow-[0_0_15px_rgba(37,99,235,0.4)] hover:shadow-[0_0_25px_rgba(37,99,235,0.6)] flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span>
                    {{ isset($address) ? 'Update Address' : 'Save Address' }}
                </button>
            </div>

        </div>
    </form>
</div>


<script>
    (function() {

        const BB_KEY = '6514c99c902f5b03b940b80c83cab5f3fe60268806737e740fb04825f468d5de';
        const BB_URL = 'https://api.binderbyte.com/wilayah';

        const inpProvince = document.getElementById('sel_province');
        const inpCity = document.getElementById('sel_city');
        const inpDistrict = document.getElementById('sel_district');
        const inpVillage = document.getElementById('sel_village');
        const inpPostal = document.getElementById('postal_code');
        const inpLat = document.getElementById('latitude');
        const inpLng = document.getElementById('longitude');
        const loadingEl = document.getElementById('regionLoading');
        const geocodeStatus = document.getElementById('geocodeStatus');
        const geocodeSpinner = document.getElementById('geocodeSpinner');
        const geocodeDone = document.getElementById('geocodeDone');
        const geocodeFail = document.getElementById('geocodeFail');

        // Nilai lama untuk re-select mode edit
        const OLD = {
            province: '{{ old("province_name", $address->province ?? "") }}',
            city: '{{ old("city_name",      $address->city      ?? "") }}',
            district: '{{ old("district_name",  $address->district  ?? "") }}',
            village: '{{ old("village_name",   $address->village   ?? "") }}',
        };

        // State nama terpilih
        const SELECTED = {
            province: '',
            city: '',
            district: '',
            village: ''
        };

        // ── Custom Dropdown Engine ───────────────────────────────────
        function setupDropdown(wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            const trigger = wrapper.querySelector('.dd-trigger');
            const menu = wrapper.querySelector('.dd-menu');
            const search = wrapper.querySelector('.dd-search');
            const list = wrapper.querySelector('.dd-list');

            trigger.addEventListener('click', () => {
                if (wrapper.classList.contains('disabled')) return;
                const isOpen = wrapper.classList.toggle('open');
                menu.classList.toggle('hidden', !isOpen);
                if (isOpen) {
                    search.value = '';
                    filterList(list, '');
                    search.focus();
                }
            });

            search.addEventListener('input', () => filterList(list, search.value));

            // Tutup jika klik di luar
            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    wrapper.classList.remove('open');
                    menu.classList.add('hidden');
                }
            });

            return wrapper;
        }

        function filterList(list, query) {
            const q = query.toLowerCase();
            list.querySelectorAll('li').forEach(li => {
                li.style.display = li.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        }

        // Isi dropdown dengan data
        function fillDropdown(wrapperId, items, placeholder, hiddenInput, nameInput, oldVal, onSelect) {
            const wrapper = document.getElementById(wrapperId);
            const list = wrapper.querySelector('.dd-list');
            const label = wrapper.querySelector('.dd-label');
            const trigger = wrapper.querySelector('.dd-trigger');

            list.innerHTML = '';
            items.forEach(item => {
                const li = document.createElement('li');
                li.dataset.id = item.id;
                li.dataset.name = item.name;
                li.dataset.postal = item.postal_code ?? '';
                li.textContent = item.name;

                if (item.name === oldVal) {
                    li.classList.add('active');
                    label.textContent = item.name;
                    label.classList.remove('text-gray-500', 'text-gray-600');
                    label.classList.add('text-white');
                    hiddenInput.value = item.id;
                    nameInput.value = item.name;
                }

                li.addEventListener('click', () => {
                    // Reset active
                    list.querySelectorAll('li').forEach(l => l.classList.remove('active'));
                    li.classList.add('active');

                    label.textContent = item.name;
                    label.classList.remove('text-gray-500', 'text-gray-600');
                    label.classList.add('text-white');

                    hiddenInput.value = item.id;
                    nameInput.value = item.name;

                    // Tutup menu
                    wrapper.classList.remove('open');
                    wrapper.querySelector('.dd-menu').classList.add('hidden');

                    if (onSelect) onSelect(item);
                });

                list.appendChild(li);
            });

            // Enable
            wrapper.classList.remove('disabled');
            trigger.disabled = false;
            trigger.classList.remove('cursor-not-allowed');
            trigger.classList.add('hover:border-blue-500/50');

            // Auto-select jika ada oldVal
            if (oldVal && hiddenInput.value) onSelect && onSelect({
                id: hiddenInput.value,
                name: oldVal
            });
        }

        // Reset dropdown ke disabled
        function resetDropdown(wrapperId, placeholder) {
            const wrapper = document.getElementById(wrapperId);
            wrapper.querySelector('.dd-list').innerHTML = '';
            wrapper.querySelector('.dd-label').textContent = placeholder;
            wrapper.querySelector('.dd-label').classList.add('text-gray-600');
            wrapper.querySelector('.dd-label').classList.remove('text-white');
            wrapper.querySelector('.dd-trigger').disabled = true;
            wrapper.classList.add('disabled');
            wrapper.classList.remove('open');
            wrapper.querySelector('.dd-menu').classList.add('hidden');
        }

        // ── Fetch JSON Binderbyte ────────────────────────────────────
        async function fetchJSON(url) {
            const res = await fetch(url);
            const json = await res.json();
            if (json.code !== '200' && json.code !== 200) throw new Error(json.messages ?? 'API error');
            return json.value;
        }

        // ── Geocoding Nominatim (koordinat + kode pos) yang Diperkuat ──────────────
        let geocodeTimer = null;

        async function geocodeAddress(queryArray) {
            geocodeStatus.classList.remove('hidden');
            geocodeSpinner.classList.remove('hidden');
            geocodeDone.classList.add('hidden');
            geocodeFail.classList.add('hidden');

            try {
                // Gabungkan array menjadi string pencarian, misal: "Kelurahan, Kecamatan, Kota, Provinsi, Indonesia"
                const query = queryArray.filter(Boolean).join(', ');
                const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=id&addressdetails=1&q=${encodeURIComponent(query)}`);
                const data = await res.json();

                if (data.length > 0) {
                    const result = data[0];
                    // Sukses dapat koordinat
                    inpLat.value = parseFloat(result.lat).toFixed(7);
                    inpLng.value = parseFloat(result.lon).toFixed(7);

                    // Coba ambil kode pos jika belum ada
                    const postcode = result.address?.postcode ?? '';
                    if (postcode && !inpPostal.value) inpPostal.value = postcode;

                    geocodeSpinner.classList.add('hidden');
                    geocodeDone.classList.remove('hidden');
                    // console.log("GPS Found: ", query); // Debugging
                } else {
                    throw new Error('Not found at this detail level');
                }
            } catch (error) {
                // JIKA GAGAL: Mundur selangkah (hapus elemen pertama/kelurahan) dan coba lagi!
                if (queryArray.length > 2) {
                    console.log("Retrying with broader area: ", queryArray.slice(1));
                    queryArray.shift(); // Buang wilayah paling spesifik (contoh: buang kelurahan)
                    await geocodeAddress(queryArray); // Coba cari lagi dengan area yang lebih luas (Kecamatan/Kota)
                } else {
                    // Benar-benar gagal (Bahkan kotanya tidak ketemu)
                    inpLat.value = '';
                    inpLng.value = '';
                    geocodeSpinner.classList.add('hidden');
                    geocodeFail.classList.remove('hidden');
                    console.log("GPS Failed completely");
                }
            }
        }

        function triggerGeocode() {
            if (!SELECTED.city || !SELECTED.province) return;

            // Buat array urutan pencarian, dari yang paling spesifik ke umum
            const queryArray = [
                SELECTED.village,
                SELECTED.district,
                SELECTED.city,
                SELECTED.province,
                'Indonesia'
            ];

            clearTimeout(geocodeTimer);
            // Delay sedikit agar tidak spam API saat user klik-klik cepat
            geocodeTimer = setTimeout(() => geocodeAddress(queryArray), 800);
        }
        // ── Load functions ───────────────────────────────────────────
        async function loadProvinces() {
            loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/provinsi?api_key=${BB_KEY}`);
                fillDropdown('dd_province', data, '— Pilih Provinsi —', inpProvince,
                    document.getElementById('province_name'), OLD.province,
                    (item) => {
                        SELECTED.province = item.name;
                        loadCities(item.id);
                    }
                );
            } catch (e) {
                console.error('Gagal load provinsi:', e);
            } finally {
                loadingEl.classList.add('hidden');
            }
        }

        async function loadCities(provinceId) {
            resetDropdown('dd_city', '— Pilih Kota —');
            resetDropdown('dd_district', '— Pilih Kecamatan —');
            resetDropdown('dd_village', '— Pilih Kelurahan —');
            inpCity.value = '';
            inpDistrict.value = '';
            inpVillage.value = '';
            inpPostal.value = '';

            loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/kabupaten?api_key=${BB_KEY}&id_provinsi=${provinceId}`);
                fillDropdown('dd_city', data, '— Pilih Kota —', inpCity,
                    document.getElementById('city_name'), OLD.city,
                    (item) => {
                        SELECTED.city = item.name;
                        loadDistricts(item.id);
                    }
                );
            } catch (e) {
                console.error('Gagal load kota:', e);
            } finally {
                loadingEl.classList.add('hidden');
            }
        }

        async function loadDistricts(cityId) {
            resetDropdown('dd_district', '— Pilih Kecamatan —');
            resetDropdown('dd_village', '— Pilih Kelurahan —');
            inpDistrict.value = '';
            inpVillage.value = '';
            inpPostal.value = '';

            loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/kecamatan?api_key=${BB_KEY}&id_kabupaten=${cityId}`);
                fillDropdown('dd_district', data, '— Pilih Kecamatan —', inpDistrict,
                    document.getElementById('district_name'), OLD.district,
                    (item) => {
                        SELECTED.district = item.name;
                        loadVillages(item.id);
                    }
                );
            } catch (e) {
                console.error('Gagal load kecamatan:', e);
            } finally {
                loadingEl.classList.add('hidden');
            }
        }

        async function loadVillages(districtId) {
            resetDropdown('dd_village', '— Pilih Kelurahan —');
            inpVillage.value = '';
            inpPostal.value = '';

            loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/kelurahan?api_key=${BB_KEY}&id_kecamatan=${districtId}`);
                fillDropdown('dd_village', data, '— Pilih Kelurahan —', inpVillage,
                    document.getElementById('village_name'), OLD.village,
                    (item) => {
                        SELECTED.village = item.name;
                        if (item.postal_code) inpPostal.value = item.postal_code;
                        triggerGeocode();
                    }
                );
            } catch (e) {
                console.error('Gagal load kelurahan:', e);
            } finally {
                loadingEl.classList.add('hidden');
            }
        }

        // ── Init semua dropdown ──────────────────────────────────────
        setupDropdown('dd_province');
        setupDropdown('dd_city');
        setupDropdown('dd_district');
        setupDropdown('dd_village');

        loadProvinces();

    })();
</script>

@endsection