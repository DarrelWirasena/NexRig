<x-app-layout>
    <main class="flex-1 flex flex-col w-full max-w-[1440px] mx-auto px-4 md:px-10 py-6">
        
        {{-- Header & Breadcrumbs --}}
        <div class="mb-8">
            <h1 class="text-gray-900 dark:text-white text-3xl md:text-4xl font-bold">High-Performance Gaming PCs</h1>
            <p class="text-gray-500 dark:text-[#929bc9]">Showing {{ $products->count() }} premium builds</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar --}}
            <aside class="w-full lg:w-64 shrink-0">
                
                <x-filter-section title="Categories" subtitle="Find your perfect rig">
                    <button class="flex items-center gap-3 px-3 py-2 bg-primary/10 text-primary rounded-lg text-sm font-medium">
                        <span class="material-symbols-outlined text-[20px]">computer</span> All Models
                    </button>
                    {{-- Loop categories from backend --}}
                </x-filter-section>

                <x-filter-section title="Specs">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-sm text-gray-600 dark:text-[#929bc9]">NVIDIA RTX 4090</span>
                    </label>
                </x-filter-section>

            </aside>

            {{-- Product Grid --}}
            <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <x-product-card 
                            :name="$product->name"
                            :price="$product->price"
                            :description="$product->short_description"
                            :image="$product->image_url ?? 'default.jpg'"
                            badge="Best Seller"
                            rating="4.9"
                            :specs="['i9-13900K', 'RTX 4090', '64GB DDR5']" {{-- Ini nanti diambil dari tabel components --}}
                        />
                    @empty
                        <p class="text-white">No rigs found.</p>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $products->links() }} {{-- Laravel default pagination --}}
                </div>
            </div>
        </div>
    </main>
</x-app-layout>