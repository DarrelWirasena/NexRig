{{-- resources/views/components/address-form-fields.blade.php --}}
@props(['address' => null])

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
    {{-- 1. LABEL ALAMAT --}}
    <div class="col-span-1 md:col-span-2">
        <label class="block text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Address Label</label>
        <div class="flex flex-wrap gap-4">
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
            <input type="text" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name ?? '') }}" placeholder="e.g. Alex Rivers" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
        </div>
    </div>

    {{-- 3. PHONE NUMBER --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Phone Number</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">call</span>
            </div>
            <input type="text" name="phone" value="{{ old('phone', $address->phone ?? '') }}" placeholder="+62 ..." class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
        </div>
    </div>

    {{-- 4. CITY --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">City</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">location_city</span>
            </div>
            <input type="text" name="city" value="{{ old('city', $address->city ?? '') }}" placeholder="e.g. Jakarta Selatan" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
        </div>
    </div>

    {{-- 5. POSTAL CODE --}}
    <div>
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Postal Code</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">markunread_mailbox</span>
            </div>
            <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" placeholder="e.g. 12xxx" class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">
        </div>
    </div>

    {{-- 6. FULL ADDRESS --}}
    <div class="col-span-1 md:col-span-2">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Full Address</label>
        <div class="relative">
            <div class="absolute top-3 left-0 flex items-start pl-4 pointer-events-none">
                <span class="text-gray-500 material-symbols-outlined text-lg">home_pin</span>
            </div>
            <textarea name="full_address" rows="3" placeholder="Street name, Building, Floor, Unit no..." class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-all">{{ old('full_address', $address->full_address ?? '') }}</textarea>
        </div>
    </div>
</div>