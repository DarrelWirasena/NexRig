<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): array
    {
        return [
            TextInput::make('code')
                ->label('Coupon Code')
                ->required()
                ->unique(ignoreRecord: true)
                ->alphaNum()
                ->placeholder('e.g. NEXRIG10')
                ->dehydrateStateUsing(fn($state) => strtoupper($state)),

            Select::make('type')
                ->label('Discount Type')
                ->required()
                ->options([
                    'percentage' => 'Percentage (%)',
                    'fixed'      => 'Fixed Amount (Rp)',
                ]),

            TextInput::make('value')
                ->label('Discount Value')
                ->required()
                ->numeric()
                ->minValue(1)
                ->helperText('For percentage: enter 10 for 10%. For fixed: enter 50000 for Rp50.000'),

            TextInput::make('min_purchase')
                ->label('Minimum Purchase (Rp)')
                ->numeric()
                ->default(0)
                ->helperText('Minimum cart total to use this coupon. 0 = no minimum.'),

            TextInput::make('max_uses')
                ->label('Maximum Uses')
                ->numeric()
                ->nullable()
                ->helperText('Leave empty for unlimited uses.'),

            TextInput::make('used_count')
                ->label('Used Count')
                ->numeric()
                ->default(0)
                ->disabled(),

            DateTimePicker::make('expires_at')
                ->label('Expiry Date')
                ->nullable()
                ->helperText('Leave empty for no expiry.'),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true),
        ];
    }
}