@props(['name', 'price', 'description', 'image', 'badge' => null])

<div class="group flex flex-col gap-4 p-4 rounded-xl bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark hover:border-primary/50 transition-all hover:shadow-xl hover:shadow-primary/5 cursor-pointer relative overflow-hidden">
    @if($badge)
        <div class="absolute top-0 right-0 bg-primary text-white text-xs font-bold px-3 py-1 rounded-bl-lg z-10 uppercase">
            {{ $badge }}
        </div>
    @endif
    
    <div class="w-full aspect-video bg-center bg-cover rounded-lg overflow-hidden relative">
        <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
        <div class="w-full h-full bg-cover bg-center transition-transform duration-500 group-hover:scale-110" 
             style='background-image: url("{{ $image }}");'>
        </div>
    </div>
    
    <div>
        <div class="flex justify-between items-center mb-1">
            <p class="text-slate-900 dark:text-white text-xl font-bold">{{ $name }}</p>
            <span class="text-primary font-bold">Rp {{ number_format($price, 0, ',', '.') }}</span>
        </div>
        <p class="text-slate-500 dark:text-text-secondary text-sm">{{ $description }}</p>
    </div>
</div>