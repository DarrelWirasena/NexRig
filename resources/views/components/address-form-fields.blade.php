@props(['address' => null])

{{-- Hidden Input untuk Koordinat & Nama Wilayah --}}
<input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $address->latitude ?? '') }}">
<input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $address->longitude ?? '') }}">
<input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $address->province ?? '') }}">
<input type="hidden" name="city_name" id="city_name" value="{{ old('city_name', $address->city ?? '') }}">
<input type="hidden" name="district_name" id="district_name" value="{{ old('district_name', $address->district ?? '') }}">
<input type="hidden" name="village_name" id="village_name" value="{{ old('village_name', $address->village ?? '') }}">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- 1. LABEL ALAMAT --}}
    <div class="col-span-1 md:col-span-2">
        <label class="block text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Address Label <span class="text-red-500">*</span></label>
        <div class="flex flex-wrap gap-3">
            @foreach(['Home', 'Office', 'Studio', 'Other'] as $lbl)
            <label class="cursor-pointer">
                <input type="radio" name="label" value="{{ $lbl }}" class="peer sr-only"
                    {{ (old('label', $address->label ?? 'Home') == $lbl) ? 'checked' : '' }}>
                <div class="px-5 py-2 rounded-lg border border-white/10 bg-white/5 text-gray-400 text-sm font-bold uppercase transition-all peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-500 hover:bg-white/10 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">
                        {{ $lbl === 'Home' ? 'home' : ($lbl === 'Office' ? 'business' : 'place') }}
                    </span>
                    {{ $lbl }}
                </div>
            </label>
            @endforeach
        </div>
    </div>

    {{-- 2. RECIPIENT NAME --}}
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Recipient Name <span class="text-red-500">*</span></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">person</span>
            </div>
            <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name ?? '') }}" placeholder="e.g. Alex Rivers"
                class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
        </div>
    </div>

    {{-- 3. PHONE NUMBER --}}
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Phone Number <span class="text-red-500">*</span></label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">+62</span>
            <input type="text" name="phone" value="{{ old('phone', $address->phone ?? '') }}" placeholder="812 3456 7890"
                class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
        </div>
    </div>

    <div class="col-span-1 md:col-span-2 border-t border-white/10 my-2 pt-4">
        <div id="regionLoading" class="hidden mb-4 flex items-center gap-2 text-xs text-blue-400">
            <span class="material-symbols-outlined animate-spin text-sm">autorenew</span> Memuat data wilayah...
        </div>
    </div>

    {{-- 4. PROVINSI --}}
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Provinsi <span class="text-red-500">*</span></label>
        <div class="custom-select" id="dd_province">
            <button type="button" class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-400 focus:border-blue-600 transition-all">
                <span class="dd-label truncate">— Pilih Provinsi —</span>
                <span class="material-symbols-outlined text-sm shrink-0">expand_more</span>
            </button>
            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl">
                <div class="p-2 border-b border-white/10">
                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white outline-none" placeholder="Cari provinsi...">
                </div>
                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
            </div>
        </div>
        <input type="hidden" id="sel_province" name="province" value="">
    </div>

    {{-- 5. KOTA --}}
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
        <div class="custom-select disabled" id="dd_city">
            <button type="button" disabled class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-600 opacity-50 cursor-not-allowed transition-all">
                <span class="dd-label truncate">— Pilih Kota —</span>
                <span class="material-symbols-outlined text-sm shrink-0">expand_more</span>
            </button>
            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl">
                <div class="p-2 border-b border-white/10">
                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white outline-none" placeholder="Cari kota...">
                </div>
                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
            </div>
        </div>
        <input type="hidden" id="sel_city" name="city" value="">
    </div>

    {{-- 6. KECAMATAN --}}
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kecamatan <span class="text-red-500">*</span></label>
        <div class="custom-select disabled" id="dd_district">
            <button type="button" disabled class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-600 opacity-50 cursor-not-allowed transition-all">
                <span class="dd-label truncate">— Pilih Kecamatan —</span>
                <span class="material-symbols-outlined text-sm shrink-0">expand_more</span>
            </button>
            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl">
                <div class="p-2 border-b border-white/10">
                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white outline-none" placeholder="Cari kecamatan...">
                </div>
                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
            </div>
        </div>
        <input type="hidden" id="sel_district" name="district" value="">
    </div>

    {{-- 7. KELURAHAN --}}
    <div>
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
        <div class="custom-select disabled" id="dd_village">
            <button type="button" disabled class="dd-trigger w-full flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-sm text-gray-600 opacity-50 cursor-not-allowed transition-all">
                <span class="dd-label truncate">— Pilih Kelurahan —</span>
                <span class="material-symbols-outlined text-sm shrink-0">expand_more</span>
            </button>
            <div class="dd-menu hidden absolute z-50 mt-1 w-full bg-[#111] border border-white/10 rounded-lg shadow-2xl">
                <div class="p-2 border-b border-white/10">
                    <input type="text" class="dd-search w-full bg-white/5 rounded px-3 py-1.5 text-xs text-white outline-none" placeholder="Cari kelurahan...">
                </div>
                <ul class="dd-list max-h-52 overflow-y-auto py-1"></ul>
            </div>
        </div>
        <input type="hidden" id="sel_village" name="village" value="">
    </div>

    {{-- 8. KODE POS --}}
    <div class="col-span-1 md:col-span-2">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Postal Code</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">markunread_mailbox</span>
            </div>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" placeholder="Auto-filled from map or type manually"
                class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">

            {{-- Geocoding Status Indicators --}}
            <div id="geocodeStatus" class="absolute right-4 top-1/2 -translate-y-1/2 hidden flex items-center">
                <span id="geocodeSpinner" class="material-symbols-outlined animate-spin text-blue-400 text-sm hidden">autorenew</span>
                <span id="geocodeDone" class="material-symbols-outlined text-green-400 text-sm hidden">check_circle</span>
                <span id="geocodeFail" class="material-symbols-outlined text-red-400 text-sm hidden">error</span>
            </div>
        </div>
    </div>

    {{-- 9. FULL ADDRESS --}}
    <div class="col-span-1 md:col-span-2">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Full Address <span class="text-red-500">*</span></label>
        <div class="relative">
            <div class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">home_pin</span>
            </div>
            <textarea name="full_address" rows="3" placeholder="Street name, Building, Floor, Unit no..."
                class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all resize-none">{{ old('full_address', $address->full_address ?? '') }}</textarea>
        </div>
    </div>
</div>

@push('styles')
<style>
    .custom-select { position: relative; }
    .custom-select .dd-trigger { text-align: left; }
    .custom-select.open .dd-trigger { border-color: rgb(59 130 246 / 0.6) !important; background: rgb(255 255 255 / 0.07) !important; opacity: 1 !important; }
    .custom-select.open .dd-trigger .material-symbols-outlined { transform: rotate(180deg); }
    .custom-select .dd-menu { position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 999; }
    .custom-select .dd-list li { padding: 9px 14px; font-size: 13px; color: #d1d5db; cursor: pointer; transition: background 0.12s; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .custom-select .dd-list li:hover { background: rgb(37 99 235 / 0.15); color: #fff; }
    .custom-select .dd-list li.active { background: rgb(37 99 235 / 0.25); color: #60a5fa; font-weight: 700; }
    .custom-select .dd-list::-webkit-scrollbar { width: 4px; }
    .custom-select .dd-list::-webkit-scrollbar-track { background: transparent; }
    .custom-select .dd-list::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
    .custom-select.disabled .dd-trigger { cursor: not-allowed !important; opacity: 0.4 !important; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inpProvince = document.getElementById('sel_province');
        if (!inpProvince) return; 

        const BB_KEY = '6514c99c902f5b03b940b80c83cab5f3fe60268806737e740fb04825f468d5de';
        const BB_URL = 'https://api.binderbyte.com/wilayah';

        const inpCity = document.getElementById('sel_city');
        const inpDistrict = document.getElementById('sel_district');
        const inpVillage = document.getElementById('sel_village');
        const inpPostal = document.getElementById('postal_code');
        const loadingEl = document.getElementById('regionLoading');

        const inpLat = document.getElementById('latitude');
        const inpLng = document.getElementById('longitude');
        
        // Elemen Indikator
        const geocodeStatus = document.getElementById('geocodeStatus');
        const geocodeSpinner = document.getElementById('geocodeSpinner');
        const geocodeDone = document.getElementById('geocodeDone');
        const geocodeFail = document.getElementById('geocodeFail');
        
        const OLD = {
            province: '{{ old('province_name', $address->province ?? '') }}',
            city: '{{ old('city_name', $address->city ?? '') }}',
            district: '{{ old('district_name', $address->district ?? '') }}',
            village: '{{ old('village_name', $address->village ?? '') }}',
        };

        const SELECTED = { province: '', city: '', district: '', village: '' };
        
        let geocodeTimer = null;
        let abortController = null; 

        function cleanRegionName(name) {
            if(!name) return '';
            return name.replace(/^KAB\.\s+/i, 'Kabupaten ')
                       .replace(/^KOTA\s+/i, 'Kota ')
                       .replace(/^PROV\.\s+/i, 'Provinsi ');
        }

        // ── DROPDOWN ENGINE ──
        function setupDropdown(wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;
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

            document.addEventListener('click', (e) => {
                if (!wrapper.contains(e.target)) {
                    wrapper.classList.remove('open');
                    menu.classList.add('hidden');
                }
            });
        }

        function filterList(list, query) {
            const q = query.toLowerCase();
            list.querySelectorAll('li').forEach(li => {
                li.style.display = li.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        }

        function fillDropdown(wrapperId, items, placeholder, hiddenInput, nameInput, oldVal, onSelect) {
            const wrapper = document.getElementById(wrapperId);
            const list = wrapper.querySelector('.dd-list');
            const label = wrapper.querySelector('.dd-label');
            const trigger = wrapper.querySelector('.dd-trigger');

            list.innerHTML = '';
            items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.name;

                if (item.name === oldVal) {
                    li.classList.add('active');
                    label.textContent = item.name;
                    label.classList.remove('text-gray-400', 'text-gray-600');
                    label.classList.add('text-white', 'font-bold');
                    hiddenInput.value = item.id;
                    if(nameInput) nameInput.value = item.name;
                }

                li.addEventListener('click', () => {
                    list.querySelectorAll('li').forEach(l => l.classList.remove('active'));
                    li.classList.add('active');
                    label.textContent = item.name;
                    label.classList.remove('text-gray-400', 'text-gray-600');
                    label.classList.add('text-white', 'font-bold');
                    hiddenInput.value = item.id;
                    if(nameInput) nameInput.value = item.name;
                    wrapper.classList.remove('open');
                    wrapper.querySelector('.dd-menu').classList.add('hidden');
                    
                    if (onSelect) onSelect(item, true);
                });
                list.appendChild(li);
            });

            wrapper.classList.remove('disabled');
            trigger.disabled = false;
            trigger.classList.remove('opacity-50', 'cursor-not-allowed');

            if (oldVal && hiddenInput.value) {
                onSelect && onSelect({ id: hiddenInput.value, name: oldVal }, false);
            }
        }

        function resetDropdown(wrapperId, placeholder) {
            const wrapper = document.getElementById(wrapperId);
            wrapper.querySelector('.dd-list').innerHTML = '';
            wrapper.querySelector('.dd-label').textContent = placeholder;
            wrapper.querySelector('.dd-label').classList.add('text-gray-400');
            wrapper.querySelector('.dd-label').classList.remove('text-white', 'font-bold');
            wrapper.querySelector('.dd-trigger').disabled = true;
            wrapper.querySelector('.dd-trigger').classList.add('opacity-50', 'cursor-not-allowed');
            wrapper.classList.add('disabled');
            wrapper.classList.remove('open');
            wrapper.querySelector('.dd-menu').classList.add('hidden');
        }

        async function fetchJSON(url) {
            const cacheKey = 'nexrig_wilayah_' + url;
            const cachedData = sessionStorage.getItem(cacheKey);
            if (cachedData) return JSON.parse(cachedData);

            const res = await fetch(url);
            const json = await res.json();
            if (json.code !== '200' && json.code !== 200) throw new Error(json.messages ?? 'API error');
            sessionStorage.setItem(cacheKey, JSON.stringify(json.value));
            return json.value;
        }

        // 🔥 FUNGSI GEOCODE DENGAN PENGHANCUR SPINNER MUTLAK 🔥
        async function geocodeAddress(queryArray) {
            if(!geocodeStatus) return;

            // FUNGSI INI AKAN MEMAKSA SPINNER MATI TANPA PEDULI CSS CLASS APAPUN
            function updateUI(state, message = '') {
                // Pastikan container utama terlihat
                geocodeStatus.classList.remove('hidden');
                geocodeStatus.style.display = 'flex';

                // MATIKAN SEMUA IKON SECARA PAKSA (Mencegah bentrok dengan font material)
                if (geocodeSpinner) geocodeSpinner.style.display = 'none';
                if (geocodeDone) geocodeDone.style.display = 'none';
                if (geocodeFail) geocodeFail.style.display = 'none';

                // NYALAKAN IKON SESUAI STATUS
                if (state === 'loading') {
                    if (geocodeSpinner) geocodeSpinner.style.display = 'inline-block';
                } else if (state === 'success') {
                    if (geocodeDone) {
                        geocodeDone.style.display = 'inline-block';
                        geocodeDone.title = message;
                    }
                } else if (state === 'error') {
                    if (geocodeFail) {
                        geocodeFail.style.display = 'inline-block';
                        geocodeFail.title = message;
                    }
                }
            }
            
            if (abortController) abortController.abort('NEW_SEARCH');
            abortController = new AbortController();
            const signal = abortController.signal;
            let isTimeout = false;

            // MULAI LOADING
            updateUI('loading');

            let finalLat = '';
            let finalLng = '';
            let finalPostcode = '';

            const queriesToTry = [];
            let currentQuery = [...queryArray];
            
            while (currentQuery.length >= 2) {
                queriesToTry.push(currentQuery.filter(Boolean).join(', '));
                currentQuery.shift();
            }

            // ATUR TIMEOUT 15 DETIK
            const timeoutId = setTimeout(() => {
                isTimeout = true;
                if (abortController) abortController.abort('TIMEOUT');
            }, 15000);

            try {
                for (let i = 0; i < queriesToTry.length; i++) {
                    const query = queriesToTry[i];

                    // Beri jeda 1 detik antar pencarian agar tidak diblokir satpam server peta
                    if (i > 0) {
                        await new Promise(resolve => setTimeout(resolve, 1000));
                    }

                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=3&countrycodes=id&addressdetails=1&q=${encodeURIComponent(query)}`, { signal });
                        if (!res.ok) continue;
                        const data = await res.json();

                        if (data && data.length > 0) {
                            if (!finalLat && !finalLng) {
                                finalLat = parseFloat(data[0].lat).toFixed(7);
                                finalLng = parseFloat(data[0].lon).toFixed(7);
                            }

                            const withPostcode = data.find(r => r.address && r.address.postcode);
                            if (withPostcode) {
                                finalPostcode = withPostcode.address.postcode;
                            } else if (data[0].address && data[0].address.postcode) {
                                finalPostcode = data[0].address.postcode;
                            }

                            if (finalLat && finalLng && finalPostcode) break; 
                        }
                    } catch (error) {
                        if (error.name === 'AbortError') throw error;
                        console.warn('Geocode fail for:', query);
                    }
                }

                clearTimeout(timeoutId);

                // CEK HASIL AKHIR
                if (finalLat && finalLng) {
                    if(inpLat) inpLat.value = finalLat;
                    if(inpLng) inpLng.value = finalLng;

                    if (finalPostcode) {
                        if(inpPostal) inpPostal.value = finalPostcode;
                        updateUI('success', 'Kode pos ditemukan otomatis dari peta.');
                    } else {
                        updateUI('error', 'Koordinat ketemu, tapi kode pos kosong di database peta. Isi manual ya.');
                    }
                } else {
                    updateUI('error', 'Gagal melacak area di peta. Silakan isi manual.');
                }

            } catch (error) {
                if (error.name === 'AbortError') {
                    if (isTimeout) {
                        updateUI('error', 'Pencarian terlalu lama (Timeout 15s). Silakan isi kode pos manual.');
                    }
                } else {
                    updateUI('error', 'Terjadi kesalahan jaringan.');
                }
            }
        }

        function triggerGeocode() {
            if (!SELECTED.city || !SELECTED.province) return;
            
            const queryArray = [
                SELECTED.village, 
                SELECTED.district, 
                cleanRegionName(SELECTED.city), 
                cleanRegionName(SELECTED.province), 
                'Indonesia'
            ];
            
            clearTimeout(geocodeTimer);
            geocodeTimer = setTimeout(() => geocodeAddress(queryArray), 800);
        }

        // ── PANGGIL DATA PERTAMA ──
        async function loadProvinces() {
            if(loadingEl) loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/provinsi?api_key=${BB_KEY}`);
                fillDropdown('dd_province', data, '— Pilih Provinsi —', inpProvince, document.getElementById('province_name'), OLD.province, (item) => {
                    SELECTED.province = item.name;
                    loadCities(item.id);
                });
            } catch (e) { console.error(e); } finally { if(loadingEl) loadingEl.classList.add('hidden'); }
        }

        async function loadCities(provinceId) {
            resetDropdown('dd_city', '— Pilih Kota —');
            resetDropdown('dd_district', '— Pilih Kecamatan —');
            resetDropdown('dd_village', '— Pilih Kelurahan —');
            inpCity.value = ''; inpDistrict.value = ''; inpVillage.value = '';
            
            if(loadingEl) loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/kabupaten?api_key=${BB_KEY}&id_provinsi=${provinceId}`);
                fillDropdown('dd_city', data, '— Pilih Kota —', inpCity, document.getElementById('city_name'), OLD.city, (item) => {
                    SELECTED.city = item.name;
                    loadDistricts(item.id);
                });
            } catch (e) {} finally { if(loadingEl) loadingEl.classList.add('hidden'); }
        }

        async function loadDistricts(cityId) {
            resetDropdown('dd_district', '— Pilih Kecamatan —');
            resetDropdown('dd_village', '— Pilih Kelurahan —');
            inpDistrict.value = ''; inpVillage.value = '';
            
            if(loadingEl) loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/kecamatan?api_key=${BB_KEY}&id_kabupaten=${cityId}`);
                fillDropdown('dd_district', data, '— Pilih Kecamatan —', inpDistrict, document.getElementById('district_name'), OLD.district, (item) => {
                    SELECTED.district = item.name;
                    loadVillages(item.id);
                });
            } catch (e) {} finally { if(loadingEl) loadingEl.classList.add('hidden'); }
        }

        async function loadVillages(districtId) {
            resetDropdown('dd_village', '— Pilih Kelurahan —');
            inpVillage.value = '';
            
            if(loadingEl) loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/kelurahan?api_key=${BB_KEY}&id_kecamatan=${districtId}`);
                fillDropdown('dd_village', data, '— Pilih Kelurahan —', inpVillage, document.getElementById('village_name'), OLD.village, (item, isManual) => {
                    SELECTED.village = item.name;
                    
                    if (isManual) {
                        if(inpPostal) inpPostal.value = '';
                        triggerGeocode();
                    } else if (!inpPostal.value || !inpLat.value) {
                        triggerGeocode();
                    }
                });
            } catch (e) {} finally { if(loadingEl) loadingEl.classList.add('hidden'); }
        }

        setupDropdown('dd_province');
        setupDropdown('dd_city');
        setupDropdown('dd_district');
        setupDropdown('dd_village');
        
        loadProvinces();
    });
</script>
@endpush