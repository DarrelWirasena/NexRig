@props(['name', 'price', 'description', 'image', 'rating' => null, 'badge' => null, 'specs' => []])

<div class="group flex flex-col bg-white dark:bg-[#1a2036] rounded-xl overflow-hidden border border-gray-200 dark:border-[#232948] hover:border-primary transition-all duration-300 h-full">
    <div class="relative aspect-[4/3] bg-gray-100 dark:bg-black/40 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
        
        @if($badge)
            <div class="absolute top-3 left-3 bg-primary text-white text-xs font-bold px-2.5 py-1 rounded">
                {{ $badge }}
            </div>
        @endif
        
        <button class="absolute top-3 right-3 p-2 rounded-full bg-black/50 text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-primary">
            <span class="material-symbols-outlined text-[20px]">favorite</span>
        </button>
    </div>

    <div class="p-5 flex flex-col flex-1">
        <div class="flex justify-between items-start mb-2">
            <h3 class="text-gray-900 dark:text-white text-lg font-bold group-hover:text-primary transition-colors line-clamp-1">{{ $name }}</h3>
            @if($rating)
                <div class="flex items-center gap-1 text-yellow-400 text-xs font-bold shrink-0">
                    <span class="material-symbols-outlined text-[14px] fill-current">star</span> {{ $rating }}
                </div>
            @endif
        </div>
        
        <p class="text-gray-500 dark:text-[#929bc9] text-sm mb-4 line-clamp-2">{{ $description }}</p>

        {{-- Spec Pills --}}
        <div class="flex flex-wrap gap-2 mb-6 mt-auto">
            @foreach($specs as $spec)
                <span class="px-2 py-1 rounded bg-gray-100 dark:bg-[#232948] text-gray-700 dark:text-gray-300 text-xs font-medium border border-gray-200 dark:border-white/5">
                    {{ $spec }}
                </span>
            @endforeach
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-[#232948]">
            <div class="flex flex-col">
                <span class="text-gray-500 dark:text-[#929bc9] text-xs">Starting at</span>
                <span class="text-gray-900 dark:text-white text-lg font-bold">${{ number_format($price) }}</span>
            </div>
            <button class="px-4 py-2 bg-primary hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-primary/30">
                Buy Now
            </button>
        </div>
    </div>
</div>