<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Resource;
use App\Models\Coupon;
use BackedEnum;
use UnitEnum;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;
    protected static string|UnitEnum|null $navigationGroup = 'Sales';
    protected static ?int $navigationSort = 3;

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->components(
            \App\Filament\Resources\Coupons\Schemas\CouponForm::configure($schema)
        );
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return \App\Filament\Resources\Coupons\Tables\CouponsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit'   => EditCoupon::route('/{record}/edit'),
        ];
    }
}