<?php

namespace App\Filament\Resources\QuickFilters\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class QuickFilterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('keyword')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g., RTX 4090, White Build'),
                    
                Toggle::make('is_active')
                    ->label('Active Status')
                    ->default(true)
                    ->required(),
                    
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->hidden(), // Kita sembunyikan karena admin akan mengaturnya via Drag and Drop
            ]);
    }
}