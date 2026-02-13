<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\CreateAction as ActionsCreateAction;
use Filament\Actions\DeleteAction as ActionsDeleteAction;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema; // Mengikuti keinginan Anda menggunakan Schema
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class BenchmarksRelationManager extends RelationManager
{
    protected static string $relationship = 'benchmarks';

    public function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            // Filament akan melihat ke model Benchmark (model relasi ini)
            // Lalu mencari fungsi game() yang kita buat di langkah #1
            Select::make('game_id')
                ->relationship('game', 'name') 
                ->label('Pilih Game')
                ->required()
                ->searchable()
                ->preload(),

                // Input Resolusi sesuai Enum di Database
                Select::make('resolution')
                    ->label('Resolusi')
                    ->options([
                        '1080p' => '1080p',
                        '1440p' => '1440p',
                        '4k' => '4k',
                    ])
                    ->required(),

                // Input FPS (avg_fps)
                TextInput::make('avg_fps')
                    ->label('Average FPS')
                    ->numeric()
                    ->required()
                    ->suffix(' FPS'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('avg_fps')
            ->columns([
                TextColumn::make('game.name')
                    ->label('Game')
                    ->sortable(),
                
                TextColumn::make('resolution')
                    ->label('Resolusi')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('avg_fps')
                    ->label('FPS')
                    ->sortable()
                    ->badge()
                    ->color('success'),
            ])
            ->headerActions([
                ActionsCreateAction::make(),
            ])
            ->actions([
                ActionsEditAction::make(),
                ActionsDeleteAction::make(),
            ])
            ->bulkActions([
                ActionsBulkActionGroup::make([
                    ActionsDeleteBulkAction::make(),
                ]),
            ]);
    }
}