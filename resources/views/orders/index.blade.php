@extends('layouts.dashboard')

@section('content')

<div class="max-w-5xl mx-auto pb-20">
    
    {{-- HEADER HALAMAN --}}
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between border-b border-white/10 pb-6 gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-black uppercase italic tracking-tighter">
                My <span class="text-blue-600">Orders</span>
            </h1>
            <p class="text-gray-400 text-sm mt-2">Track your rigs, upgrades, and component shipments.</p>
        </div>
        
        {{-- SEARCH BAR FIX (FLEX WRAPPER METHOD) --}}
        <form action="{{ route('orders.index') }}" method="GET" class="relative w-full md:w-64">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <span class="material-symbols-outlined text-gray-500 text-[20px]">search</span>
            </div>
            <input type="text" name="search" id="order-search" value="{{ request('search') }}"
                placeholder="Search by order ID or product name..." 
                class="w-full pl-10 pr-8 py-2 input-tech rounded-lg text-sm focus:text-blue-500 placeholder-gray-600 transition-all focus:ring-1 focus:ring-blue-500 focus:border-blue-500">

                @if(request('search'))
                    <button type="button" id="order-search-clear"
                        
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                @endif
                
        </form>
    </div>

    {{-- FILTER TABS (LOGIC) --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <div class="flex bg-[#0a0a0a] p-1 rounded-xl border border-white/10 w-full sm:w-auto">
            
            {{-- TAB: ACTIVE ORDERS --}}
            <a href="{{ route('orders.index', ['tab' => 'active', 'search' => $search]) }}" 
               class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-bold transition-all text-center
               {{ $tab == 'active' 
                    ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.4)]' 
                    : 'text-gray-500 hover:text-white hover:bg-white/5' 
               }}">
                Active Orders
            </a>

            {{-- TAB: PAST ORDERS --}}
            <a href="{{ route('orders.index', ['tab' => 'past', 'search' => $search]) }}" 
               class="flex-1 sm:flex-none px-6 py-2 rounded-lg text-sm font-bold transition-all text-center
               {{ $tab == 'past' 
                    ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.4)]' 
                    : 'text-gray-500 hover:text-white hover:bg-white/5' 
               }}">
                Past Orders
            </a>

        </div>
    </div>

    {{-- ORDER LIST --}}
    <div class="space-y-6">
        @forelse($orders as $order)
            {{-- PANGGIL COMPONENT --}}
            <x-order-card :order="$order" />
        @empty
            {{-- EMPTY STATE --}}
            <div class="text-center py-20 bg-[#0a0a0a] border border-dashed border-white/10 rounded-xl">
                <span class="material-symbols-outlined text-6xl text-gray-700 mb-4">package_2</span>
                <h3 class="text-xl font-bold text-white mb-2">
                    No {{ $tab == 'active' ? 'active' : 'past' }} orders found
                </h3>
                <p class="text-gray-500 text-sm mb-6">
                    {{ $tab == 'active' ? "You don't have any orders in progress." : "You haven't completed any orders yet." }}
                </p>
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-white text-black font-bold rounded hover:bg-gray-200 transition-colors inline-block">
                    Browse Catalog
                </a>
            </div>
        @endforelse
    </div>

    {{-- SUPPORT BANNER --}}
    <x-support-banner />

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderSearch = document.getElementById('order-search');
        const clearBtn = document.getElementById('order-search-clear');

        console.log('orderSearch:', orderSearch);
        console.log('clearBtn:', clearBtn);

        if (orderSearch) {
            orderSearch.addEventListener('input', function() {
                clearTimeout(this._timer);
                this._timer = setTimeout(() => this.closest('form').submit(), 500);
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                orderSearch.value = '';
                orderSearch.closest('form').submit();
            });
        }
    });
</script>
@endpush