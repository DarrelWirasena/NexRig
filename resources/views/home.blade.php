<x-app-layout>
    <div class="w-full max-w-[1440px] px-4 md:px-10 lg:px-40 pb-20">
        
        <section class="@container py-6 md:py-8">
            <div class="relative flex min-h-[500px] flex-col gap-6 items-center justify-center p-8 md:p-12 rounded-2xl overflow-hidden group shadow-2xl shadow-primary/10">
                <div class="absolute inset-0 z-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" 
                     style='background-image: url("https://images.unsplash.com/photo-1587202372775-e229f172b9d7?q=80&w=2000");'>
                </div>
                <div class="absolute inset-0 z-0 bg-gradient-to-t from-background-dark via-background-dark/80 to-transparent"></div>
                <div class="relative z-10 flex flex-col gap-4 text-center max-w-[800px]">
                    <span class="inline-block mx-auto py-1 px-3 rounded-full bg-primary/20 border border-primary/30 text-primary text-xs font-bold uppercase tracking-wider backdrop-blur-md">New Arrivals</span>
                    <h1 class="text-white text-5xl md:text-6xl font-black leading-tight">Build Your Legacy.</h1>
                    <h2 class="text-gray-200 text-lg max-w-[600px] mx-auto">Custom-tuned rigs, engineered for maximum FPS and stunning visuals.</h2>
                    <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center">
                        <button class="bg-primary hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-bold transition-all transform hover:scale-105">Shop Now</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="flex flex-col gap-6 py-8">
            <div class="flex items-end justify-between px-2">
                <h2 class="text-3xl font-bold tracking-tight">Choose Your Power Level</h2>
                <a class="text-primary font-bold hover:underline hidden sm:block" href="#">View all categories -></a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Contoh pemanggilan komponen --}}
                <x-product-card 
                    name="Entry-Level" 
                    price="9000000" 
                    description="Great for 1080p gaming & Esports titles."
                    image="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=800"
                />
                
                <x-product-card 
                    name="Professional" 
                    price="18000000" 
                    badge="Best Seller"
                    description="1440p High Refresh Rate gaming & streaming."
                    image="https://images.unsplash.com/photo-1614014077943-840960ce6694?w=800"
                />

                <x-product-card 
                    name="Extreme" 
                    price="35000000" 
                    description="4K Ultimate Performance & VR Ready."
                    image="https://images.unsplash.com/photo-1547082299-de196ea013d6?w=800"
                />
            </div>
        </section>

    </div>
</x-app-layout>