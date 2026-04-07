@extends('layouts.app')

@section('styles')
<style>
    /* ── Custom Dropdown CSS untuk Komponen Alamat ── */
    .custom-select { position: relative; }
    .custom-select .dd-trigger { text-align: left; }
    .custom-select.open .dd-trigger { border-color: rgb(59 130 246 / 0.6) !important; background: rgb(255 255 255 / 0.07) !important; opacity: 1 !important; }
    .custom-select.open .dd-trigger .material-symbols-outlined { transform: rotate(180deg); }
    .custom-select .dd-menu { position: absolute; top: calc(100% + 4px); left: 0; right: 0; z-index: 50; }
    .custom-select .dd-list li { padding: 9px 14px; font-size: 13px; color: #d1d5db; cursor: pointer; transition: background 0.12s; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .custom-select .dd-list li:hover { background: rgb(37 99 235 / 0.15); color: #fff; }
    .custom-select .dd-list li.active { background: rgb(37 99 235 / 0.25); color: #60a5fa; font-weight: 700; }
    .custom-select .dd-list::-webkit-scrollbar { width: 4px; }
    .custom-select .dd-list::-webkit-scrollbar-track { background: transparent; }
    .custom-select .dd-list::-webkit-scrollbar-thumb { background: #374151; border-radius: 4px; }
    .custom-select.disabled .dd-trigger { cursor: not-allowed !important; opacity: 0.4 !important; }
</style>
@endsection

@section('content')

{{-- LOADING OVERLAY --}}
<div id="checkout-loading" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center">
    <div class="relative w-24 h-24 mb-4">
        <div class="absolute inset-0 border-4 border-white/10 rounded-full"></div>
        <div class="absolute inset-0 border-4 border-primary rounded-full border-t-transparent animate-spin"></div>
        <span
            class="material-symbols-outlined absolute left-1/2 top-1/2 flex h-11 w-11 -translate-x-1/2 -translate-y-1/2 items-center justify-center text-primary animate-pulse"
            style="font-size: 44px; line-height: 44px; font-variation-settings: 'FILL' 1, 'wght' 500, 'GRAD' 0, 'opsz' 48;"
            aria-hidden="true">rocket_launch</span>
    </div>
    <h3 class="text-white font-black text-2xl uppercase italic tracking-widest mb-1">Deploying Order</h3>
    <p class="text-slate-400 font-mono text-xs uppercase tracking-[0.3em]">Securing payment channel...</p>
</div>

<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6">
    {{-- 🔥 Tambahkan class no-global-loader di sini 🔥 --}}
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form" class="no-global-loader">
        @csrf

        {{-- 🔥 TAMBAHAN WAJIB: Bawa ID item yang dipilih ke Controller saat form disubmit 🔥 --}}
        @if(isset($selectedIds))
            @foreach($selectedIds as $id)
                <input type="hidden" name="selected_items[]" value="{{ $id }}">
            @endforeach
        @endif

        {{-- STEPPER --}}
        <div class="flex items-center justify-center gap-4 mb-12 select-none">
            <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400 opacity-50">
                <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">1</span>
                <span class="font-bold text-sm hidden sm:block">Inventory</span>
            </div>
            <div class="w-12 h-[1px] bg-slate-800"></div>
            <div class="flex items-center gap-2 text-primary">
                <span class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(19,55,236,0.5)]">2</span>
                <span class="font-bold text-sm uppercase tracking-wider">Deployment</span>
            </div>
            <div class="w-12 h-[1px] bg-slate-800"></div>
            <div class="flex items-center gap-2 text-slate-600 dark:text-slate-700">
                <span class="w-8 h-8 rounded-full border border-current flex items-center justify-center font-bold text-sm">3</span>
                <span class="font-bold text-sm hidden sm:block">Payment</span>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12 items-start">

            {{-- ========================= --}}
            {{-- KOLOM KIRI               --}}
            {{-- ========================= --}}
            <div class="w-full lg:w-2/3 space-y-8">

                {{-- A. SHIPPING SECTION --}}
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>

                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">pin_drop</span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Drop Zone Location</h2>
                    </div>

                    @if($address)
                        {{-- A. JIKA USER SUDAH PUNYA ALAMAT --}}
                        <input type="hidden" name="recipient_name" value="{{ $address->recipient_name }}">
                        <input type="hidden" name="phone" value="{{ $address->phone }}">
                        <input type="hidden" name="full_address" value="{{ $address->full_address }}">
                        <input type="hidden" name="city" value="{{ $address->city }}">
                        <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">
                        <input type="hidden" name="latitude" value="{{ $address->latitude }}">
                        <input type="hidden" name="longitude" value="{{ $address->longitude }}">

                        <div class="relative bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-white/5 rounded-xl p-6 transition-all hover:border-primary/50">
                            <a href="{{ route('address.index', ['origin' => 'checkout']) }}"
                                class="absolute top-4 right-4 text-[10px] font-bold text-slate-500 hover:text-white bg-white dark:bg-white/5 hover:bg-primary border border-slate-200 dark:border-white/10 px-3 py-1.5 rounded-lg transition-all uppercase tracking-widest flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">edit_location</span> Change
                            </a>
                            <div class="flex items-start gap-4">
                                <div class="mt-1 p-3 bg-white dark:bg-[#050505] rounded-lg border border-slate-200 dark:border-white/10 text-primary shadow-sm shrink-0">
                                    <span class="material-symbols-outlined">satellite_alt</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="font-bold text-slate-900 dark:text-white text-lg">{{ $address->recipient_name }}</span>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-primary/10 text-primary border border-primary/20">{{ $address->label }}</span>
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400 font-mono text-xs mb-2 tracking-tight">{{ $address->phone }}</p>
                                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed max-w-md">{{ $address->full_address }}</p>
                                    <div class="flex items-center gap-4 mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        <span>{{ $address->city }}</span>
                                        <span class="w-1 h-1 bg-slate-600 rounded-full"></span>
                                        <span>{{ $address->postal_code }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- B. JIKA USER BELUM PUNYA ALAMAT --}}
                        <div class="rounded-xl border border-dashed border-slate-300 dark:border-white/20 bg-slate-50/50 dark:bg-[#0a0a0a] p-6 sm:p-8">
                            <div class="mb-6 flex items-start gap-4 p-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20 text-yellow-500">
                                <span class="material-symbols-outlined text-2xl animate-pulse">warning</span>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest mb-1">Location Data Missing</h4>
                                    <p class="text-[11px] opacity-80 leading-relaxed">System requires valid delivery coordinates to initialize deployment logic.</p>
                                </div>
                            </div>

                            @if($errors->any())
                            <div class="flex items-start gap-4 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 mb-6">
                                <span class="material-symbols-outlined text-2xl">error</span>
                                <div>
                                    <h4 class="text-xs font-black uppercase tracking-widest mb-1">Validation Error</h4>
                                    <p class="text-[11px] opacity-80 leading-relaxed">{{ $errors->first() }}</p>
                                </div>
                            </div>
                            @endif

                            {{-- MEMANGGIL KOMPONEN FORM ALAMAT --}}
                            <x-address-form-fields />

                            <input type="hidden" name="is_default" value="1">
                        </div>
                    @endif
                </div>

                {{-- B. PAYMENT SECTION --}}
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-white/5 pb-4">
                        <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white uppercase italic tracking-widest">Payment Protocol</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="bank_transfer" checked class="peer sr-only">
                            <div class="h-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#111422] peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                <span class="material-symbols-outlined text-2xl text-slate-400 peer-checked:text-primary">account_balance</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-300 peer-checked:text-primary">Virtual Account</span>
                                    <span class="text-[8px] text-slate-400 uppercase tracking-tighter">BCA, Mandiri, BNI, BRI</span>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_type" value="qris" class="peer sr-only">
                            <div class="h-full flex items-center gap-4 p-4 rounded-xl border-2 border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#111422] peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                                <span class="material-symbols-outlined text-2xl text-slate-400 peer-checked:text-primary">qr_code_scanner</span>
                                <div class="flex flex-col">
                                    <span class="font-bold text-[10px] uppercase tracking-widest text-slate-500 dark:text-slate-300 peer-checked:text-primary">QRIS / E-Wallet</span>
                                    <span class="text-[8px] text-slate-400 uppercase tracking-tighter">Gopay, OVO, Dana, LinkAja</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            {{-- ========================= --}}
            {{-- KOLOM KANAN              --}}
            {{-- ========================= --}}
            <div class="w-full lg:w-1/3 lg:sticky lg:top-24">
                <div class="bg-white dark:bg-[#0a0a0a] p-6 sm:p-8 rounded-xl border border-slate-200 dark:border-white/10 shadow-2xl">

                    <h2 class="text-lg font-black mb-6 text-slate-900 dark:text-white uppercase italic tracking-widest flex items-center gap-2">
                        <span class="w-1 h-5 bg-primary block"></span> Hardware Manifest
                    </h2>

                    {{-- Item List --}}
                    <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 custom-sidebar-scroll mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex gap-4 p-3 rounded-lg bg-slate-50 dark:bg-[#111422] border border-slate-200 dark:border-white/5">
                            <div class="w-14 h-14 rounded overflow-hidden flex-shrink-0 bg-white dark:bg-black border border-slate-200 dark:border-white/10">
                                <img src="{{ $item->image }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col justify-between py-0.5 flex-1">
                                <p class="font-bold text-[11px] text-slate-900 dark:text-white line-clamp-1 uppercase tracking-wide">{{ $item->name }}</p>
                                <div class="flex justify-between items-center mt-1">
                                    <span class="text-[10px] text-slate-500 font-mono bg-white dark:bg-black px-1.5 py-0.5 rounded border border-slate-200 dark:border-white/10">x{{ $item->quantity }}</span>
                                    <p class="text-primary font-bold text-xs font-mono">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Coupon Input --}}
                    <div class="mb-6" id="coupon-section">
                        @php $couponSession = session('coupon'); @endphp

                        @if($couponSession)
                            {{-- Coupon Applied State --}}
                            <div class="flex items-center justify-between p-3 rounded-lg bg-green-500/10 border border-green-500/30">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-green-400 text-lg">confirmation_number</span>
                                    <div>
                                        <p class="text-green-400 font-black text-xs uppercase tracking-widest">{{ $couponSession['code'] }}</p>
                                        <p class="text-green-500/70 text-[10px]">Discount applied</p>
                                    </div>
                                </div>
                                <button type="button" onclick="removeCoupon()"
                                    class="text-gray-500 hover:text-red-400 transition-colors">
                                    <span class="material-symbols-outlined text-sm">close</span>
                                </button>
                            </div>
                        @else
                            {{-- Coupon Input State --}}
                            <button type="button" onclick="toggleCoupon()"
                                class="text-[10px] font-bold text-slate-500 hover:text-primary uppercase tracking-widest transition-colors flex items-center gap-1"
                                id="coupon-toggle">
                                <span class="material-symbols-outlined text-sm">confirmation_number</span>
                                Have a coupon code?
                            </button>

                            <div id="coupon-input-area" class="hidden mt-3">
                                <div class="flex gap-2">
                                    <input type="text" id="coupon-code"
                                        placeholder="Enter code..."
                                        class="flex-1 bg-white/5 border border-white/10 focus:border-primary text-white text-xs rounded-lg px-3 py-2.5 outline-none transition-colors uppercase font-mono tracking-widest placeholder:normal-case placeholder:tracking-normal placeholder:font-sans">
                                    <button type="button" onclick="applyCoupon()"
                                        id="coupon-apply-btn"
                                        class="px-4 py-2.5 bg-primary hover:bg-blue-600 text-white text-xs font-black uppercase tracking-wider rounded-lg transition-colors">
                                        Apply
                                    </button>
                                </div>
                                <p id="coupon-message" class="text-[10px] mt-1.5 hidden"></p>
                            </div>
                        @endif
                    </div>

                    {{-- Calculation --}}
                    <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-white/10 mb-8 font-mono text-xs">
                        <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                            <span class="uppercase tracking-widest font-bold">Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        @if($couponSession)
                        <div class="flex justify-between text-green-400">
                            <span class="uppercase tracking-widest font-bold">Discount</span>
                            <span>- Rp {{ number_format($couponSession['discount'], 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="flex justify-between text-slate-500 dark:text-[#929bc9]">
                            <span class="uppercase tracking-widest font-bold">Tax (11%)</span>
                            <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100 dark:border-white/5 mt-2">
                            <span class="text-sm font-black text-slate-900 dark:text-white uppercase italic tracking-widest font-display">Grand Total</span>
                            <span class="text-xl font-black text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- ACTION BUTTON --}}
                    <button type="submit" class="w-full bg-primary hover:bg-blue-600 text-white font-black py-4 rounded-xl shadow-[0_0_20px_rgba(19,55,236,0.4)] hover:shadow-[0_0_30px_rgba(19,55,236,0.6)] transition-all flex items-center justify-center gap-2 group uppercase italic tracking-[0.2em] text-sm relative overflow-hidden">
                        <span class="relative z-10">Deploy Order</span>
                        <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform relative z-10">rocket_launch</span>
                    </button>

                    <div class="mt-6 text-center">
                        <div class="flex items-center justify-center gap-2 text-[10px] text-slate-400 uppercase tracking-widest opacity-70">
                            <span class="material-symbols-outlined text-sm">lock</span>
                            <span>Secure SSL Encrypted</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ── 1. SCRIPT LOADING OVERLAY NEXRIG (TETAP ADA) ──
        const checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                if (!this.checkValidity()) return;
                const btn = this.querySelector('button[type="submit"]');
                const loadingOverlay = document.getElementById('checkout-loading');
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                loadingOverlay.classList.remove('hidden');
                loadingOverlay.classList.add('flex');
            });
        }

        // ── 2. SCRIPT API WILAYAH (HANYA AKTIF JIKA ADA FORM ALAMAT BARU) ──
        const inpProvince = document.getElementById('sel_province');
        // Jika input sel_province tidak ada (artinya user sudah punya alamat), hentikan eksekusi script ini
        if (!inpProvince) return; 

        const BB_KEY = '6514c99c902f5b03b940b80c83cab5f3fe60268806737e740fb04825f468d5de';
        const BB_URL = 'https://api.binderbyte.com/wilayah';

        const inpCity = document.getElementById('sel_city');
        const inpDistrict = document.getElementById('sel_district');
        const inpVillage = document.getElementById('sel_village');
        const inpPostal = document.getElementById('postal_code');
        const loadingEl = document.getElementById('regionLoading');

        const SELECTED = { province: '', city: '', district: '', village: '' };

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
            return wrapper;
        }

        function filterList(list, query) {
            const q = query.toLowerCase();
            list.querySelectorAll('li').forEach(li => {
                li.style.display = li.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        }

        function fillDropdown(wrapperId, items, placeholder, hiddenInput, nameInput, onSelect) {
            const wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;
            const list = wrapper.querySelector('.dd-list');
            const label = wrapper.querySelector('.dd-label');
            const trigger = wrapper.querySelector('.dd-trigger');

            list.innerHTML = '';
            items.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.name;
                li.addEventListener('click', () => {
                    list.querySelectorAll('li').forEach(l => l.classList.remove('active'));
                    li.classList.add('active');
                    label.textContent = item.name;
                    
                    label.classList.remove('text-gray-400', 'text-gray-500');
                    label.classList.add('text-slate-900', 'dark:text-white', 'font-bold');
                    
                    hiddenInput.value = item.id;
                    if(nameInput) nameInput.value = item.name;

                    wrapper.classList.remove('open');
                    wrapper.querySelector('.dd-menu').classList.add('hidden');
                    if (onSelect) onSelect(item);
                });
                list.appendChild(li);
            });

            wrapper.classList.remove('disabled');
            trigger.disabled = false;
            trigger.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        function resetDropdown(wrapperId, placeholder) {
            const wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;
            wrapper.querySelector('.dd-list').innerHTML = '';
            wrapper.querySelector('.dd-label').textContent = placeholder;
            wrapper.querySelector('.dd-label').classList.add('text-gray-400');
            wrapper.querySelector('.dd-label').classList.remove('text-slate-900', 'dark:text-white', 'font-bold');
            wrapper.querySelector('.dd-trigger').disabled = true;
            wrapper.querySelector('.dd-trigger').classList.add('opacity-50', 'cursor-not-allowed');
            wrapper.classList.add('disabled');
            wrapper.classList.remove('open');
            wrapper.querySelector('.dd-menu').classList.add('hidden');
        }

        async function fetchJSON(url) {
            const res = await fetch(url);
            const json = await res.json();
            if (json.code !== '200' && json.code !== 200) throw new Error(json.messages ?? 'API error');
            return json.value;
        }

        async function loadProvinces() {
            if(loadingEl) loadingEl.classList.remove('hidden');
            try {
                const data = await fetchJSON(`${BB_URL}/provinsi?api_key=${BB_KEY}`);
                fillDropdown('dd_province', data, '— Pilih Provinsi —', inpProvince, document.getElementById('province_name'), (item) => {
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
                fillDropdown('dd_city', data, '— Pilih Kota —', inpCity, document.getElementById('city_name'), (item) => {
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
                fillDropdown('dd_district', data, '— Pilih Kecamatan —', inpDistrict, document.getElementById('district_name'), (item) => {
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
                fillDropdown('dd_village', data, '— Pilih Kelurahan —', inpVillage, document.getElementById('village_name'), (item) => {
                    SELECTED.village = item.name;
                });
            } catch (e) {} finally { if(loadingEl) loadingEl.classList.add('hidden'); }
        }

        setupDropdown('dd_province');
        setupDropdown('dd_city');
        setupDropdown('dd_district');
        setupDropdown('dd_village');
        
        loadProvinces();
    });

    // ── COUPON SYSTEM ──
    function toggleCoupon() {
        const area = document.getElementById('coupon-input-area');
        area.classList.toggle('hidden');
    }

    function applyCoupon() {
        const code = document.getElementById('coupon-code').value.trim();
        const btn = document.getElementById('coupon-apply-btn');
        const msg = document.getElementById('coupon-message');

        if (!code) return;

        btn.disabled = true;
        btn.textContent = '...';
        msg.classList.add('hidden');

        fetch('{{ route("coupon.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Reload page to reflect new totals
                window.location.reload();
            } else {
                msg.textContent = data.message;
                msg.className = 'text-[10px] mt-1.5 text-red-400';
                msg.classList.remove('hidden');
            }
        })
        .catch(() => {
            msg.textContent = 'Failed to apply coupon. Try again.';
            msg.className = 'text-[10px] mt-1.5 text-red-400';
            msg.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Apply';
        });
    }

    function removeCoupon() {
        fetch('{{ route("coupon.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(() => window.location.reload());
    }
</script>
@endpush