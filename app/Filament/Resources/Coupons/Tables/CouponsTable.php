<?php

namespace App\Filament\Resources\Coupons\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('primary'),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn($state) => $state === 'percentage' ? 'success' : 'warning'),

                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(fn($record) => $record->formattedValue()),

                TextColumn::make('min_purchase')
                    ->label('Min Purchase')
                    ->money('IDR'),

                TextColumn::make('used_count')
                    ->label('Uses')
                    ->formatStateUsing(fn($record) => $record->max_uses
                        ? $record->used_count . ' / ' . $record->max_uses
                        : $record->used_count . ' / ∞'),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime('d M Y')
                    ->placeholder('Never'),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}