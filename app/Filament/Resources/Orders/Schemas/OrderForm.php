<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('order_date')
                    ->required(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('shipping_name'),
                TextInput::make('shipping_phone')
                    ->tel(),
                Textarea::make('shipping_address')
                    ->columnSpanFull(),
                TextInput::make('shipping_city'),
                TextInput::make('shipping_postal_code'),
            ]);
    }
}
