<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

  public function form(Schema $schema): Schema
{
    return $schema
        ->components([
            FileUpload::make('image_url')
                ->label('Product Image')
                ->disk('cloudinary')
                ->directory('products')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->required()
                ->getUploadedFileNameForStorageUsing(function ($file) {
                    // Hapus extension dari nama file yang disimpan
                    return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                }),
                
            Toggle::make('is_primary')
                ->label('Set as Primary Image')
                ->default(false),
                
            TextInput::make('sort_order')
                ->label('Sort Order')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
}

public function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('image_url')
        ->columns([
            ImageColumn::make('image_url')
                ->label('Image')
                ->disk('cloudinary')
                ->square()
                ->size(80),
            // ... kolom lainnya
        ])
             ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
