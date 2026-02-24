<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // === USERS ===
        $totalUsers = User::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->count();
        $newUsersLastMonth = User::whereMonth('created_at', now()->subMonth()->month)->count();
        $userTrend = collect(range(5, 0))->map(fn($i) => User::whereMonth('created_at', now()->subMonths($i)->month)->count())->toArray();

        // === PRODUCTS ===
        $totalProducts = Product::count();

        // === ORDERS ===
        $totalOrders = Order::count();
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)->count();
        $ordersLastMonth = Order::whereMonth('created_at', now()->subMonth()->month)->count();
        $orderTrend = collect(range(5, 0))->map(fn($i) => Order::whereMonth('created_at', now()->subMonths($i)->month)->count())->toArray();
        $orderDiff = $ordersThisMonth - $ordersLastMonth;

        // === REVENUE ===
        $revenueThisMonth = Order::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('total_price');
        $revenueLastMonth = Order::where('status', 'completed')->whereMonth('created_at', now()->subMonth()->month)->sum('total_price');
        $revenueTrend = collect(range(5, 0))->map(fn($i) => Order::where('status', 'completed')->whereMonth('created_at', now()->subMonths($i)->month)->sum('total_price'))->toArray();
        $revenueDiff = $revenueThisMonth - $revenueLastMonth;

        return [
            // STAT 1: USERS
            Stat::make('Total Users', number_format($totalUsers))
                ->description($newUsersThisMonth > $newUsersLastMonth
                    ? '↑ ' . $newUsersThisMonth . ' user baru bulan ini'
                    : '↓ ' . $newUsersThisMonth . ' user baru bulan ini')
                ->descriptionIcon($newUsersThisMonth >= $newUsersLastMonth
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($newUsersThisMonth >= $newUsersLastMonth ? 'success' : 'danger')
                ->icon('heroicon-o-users')
                ->chart($userTrend),

            // STAT 2: PRODUCTS
            Stat::make('Total Products', number_format($totalProducts))
                ->description('Produk aktif di katalog')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info')
                ->icon('heroicon-o-shopping-bag'),

            // STAT 3: ORDERS BULAN INI
            Stat::make('Orders Bulan Ini', number_format($ordersThisMonth))
                ->description($orderDiff >= 0
                    ? '↑ ' . abs($orderDiff) . ' lebih banyak dari bulan lalu'
                    : '↓ ' . abs($orderDiff) . ' lebih sedikit dari bulan lalu')
                ->descriptionIcon($orderDiff >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($orderDiff >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-shopping-cart')
                ->chart($orderTrend),

            
                // STAT 4: ORDER STATUS
        Stat::make('Order Status', 
            Order::where('status', 'completed')->count() . ' Completed')
            ->description(
                ' Pending: ' . Order::where('status', 'pending')->count() .
                '  |   Processing: ' . Order::where('status', 'processing')->count() .
                '  |   Shipped: ' . Order::where('status', 'shipped')->count() .
                '  |   Cancelled: ' . Order::where('status', 'cancelled')->count()
            )
            ->descriptionIcon('heroicon-m-clipboard-document-list')
            ->color('success')
            ->icon('heroicon-o-clipboard-document-list'),

            // STAT 5: REVENUE BULAN INI
            Stat::make('Revenue Bulan Ini', 'Rp ' . number_format($revenueThisMonth, 0, ',', '.'))
                ->description($revenueDiff >= 0
                    ? '↑ Rp ' . number_format(abs($revenueDiff), 0, ',', '.') . ' dari bulan lalu'
                    : '↓ Rp ' . number_format(abs($revenueDiff), 0, ',', '.') . ' dari bulan lalu')
                ->descriptionIcon($revenueDiff >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down')
                ->color($revenueDiff >= 0 ? 'success' : 'danger')
                ->icon('heroicon-o-banknotes')
                ->chart($revenueTrend),

            // STAT 6: TOTAL REVENUE
            Stat::make('Total Revenue', 'Rp ' . number_format(
                Order::where('status', 'completed')->sum('total_price'), 0, ',', '.'
            ))
                ->description('Completed: ' . Order::where('status', 'completed')->count() . ' orders')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->icon('heroicon-o-currency-dollar'),
        ];
    }
}