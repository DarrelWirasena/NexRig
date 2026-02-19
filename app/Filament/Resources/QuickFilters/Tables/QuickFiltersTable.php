<?php

namespace App\Filament\Resources\QuickFilters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn; // Kita import ToggleColumn
use Filament\Tables\Table;

class QuickFiltersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('keyword')
                    ->searchable()
                    ->weight('bold'), // Sedikit styling agar lebih tegas
                    
                ToggleColumn::make('is_active') // Mengganti IconColumn menjadi ToggleColumn
                    ->label('Active'),
                    
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // --- FITUR SAKTI DITAMBAHKAN DI SINI ---
            ->reorderable('order') // Mengaktifkan fitur Drag & Drop
            ->defaultSort('order', 'asc') // Default urutan berdasar hasil Drag & Drop
            // ---------------------------------------
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}