<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
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
                    ->prefix('Rp'),

                Select::make('status')
                    ->label('Status')
                    ->required()
                    ->default('pending')
                    ->options([
                        'pending'    => 'Menunggu Pembayaran',
                        'processing' => 'Dikemas',
                        'shipped'    => 'Dikirim',
                        'completed'  => 'Selesai',
                        'cancelled'  => 'Dibatalkan',
                    ])
                    ->native(false),

                TextInput::make('shipping_name')
                    ->label('Nama Penerima'),
                TextInput::make('shipping_phone')
                    ->label('Nomor Telepon')
                    ->tel(),
                Textarea::make('shipping_address')
                    ->label('Alamat Pengiriman')
                    ->columnSpanFull(),
                TextInput::make('shipping_city')
                    ->label('Kota'),
                TextInput::make('shipping_postal_code')
                    ->label('Kode Pos'),
            ]);
    }
}