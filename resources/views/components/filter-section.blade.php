@props(['title', 'subtitle' => null])

<div class="bg-white dark:bg-[#111422] rounded-xl lg:border border-gray-200 dark:border-[#232948] lg:p-4 mb-6">
    <div class="flex flex-col gap-1 mb-4">
        <h3 class="text-gray-900 dark:text-white text-lg font-bold">{{ $title }}</h3>
        @if($subtitle)
            <p class="text-gray-500 dark:text-[#929bc9] text-sm">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="flex flex-col gap-2">
        {{ $slot }}
    </div>
</div>