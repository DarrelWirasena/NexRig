<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('series.name')
                    ->label('Series')
                    ->sortable(),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('tier')
                    ->badge(),

                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),

                // ── Stock columns ────────────────────────────────────────────
                TextColumn::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => match (true) {
                        !$record->track_stock    => 'gray',
                        $record->stock === 0     => 'danger',
                        $record->stock <= 5      => 'warning',
                        default                  => 'success',
                    })
                    ->formatStateUsing(fn ($record) => $record->track_stock
                        ? $record->stock . ' unit'
                        : '∞ unlimited'
                    ),
                // ─────────────────────────────────────────────────────────────

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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