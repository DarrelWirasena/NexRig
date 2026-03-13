<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_series_id')
                    ->required()
                    ->numeric(),

                TextInput::make('name')
                    ->required(),

                TextInput::make('slug')
                    ->required(),

                Select::make('tier')
                    ->options([
                        'Core'    => 'Core',
                        'Pro'     => 'Pro',
                        'Elite'   => 'Elite',
                        'Creator' => 'Creator',
                        'Extreme' => 'Extreme',
                    ]),

                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                // ── Stock tracking ───────────────────────────────────────────
                Toggle::make('track_stock')
                    ->label('Lacak stok')
                    ->helperText('Nonaktifkan untuk produk pre-order atau unlimited.')
                    ->default(true)
                    ->live(),  // ← reactive: sembunyikan field stock jika toggle off

                TextInput::make('stock')
                    ->label('Jumlah stok')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->suffix('unit')
                    ->visible(fn ($get) => $get('track_stock')),  // ← hanya tampil jika track_stock = true
                // ────────────────────────────────────────────────────────────

                TextInput::make('short_description'),

                Textarea::make('description')
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}