<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Helpers\CloudinaryHelper;
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
use Filament\Schemas\Components\Utilities\Set;
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
                FileUpload::make('image_upload')
                    ->label('Product Image')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5120)
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) return;

                        try {
                            $url = CloudinaryHelper::upload($state, 'products');
                            $set('image_url', $url);
                        } catch (\Exception $e) {
                            //
                        }
                    })
                    ->dehydrated(false),

                TextInput::make('image_url')
                    ->label('Image URL (from Cloudinary)')
                    ->readOnly()
                    ->hidden(fn ($get) => !$get('image_url')),

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
                    ->square()
                    ->size(80),
                IconColumn::make('is_primary')
                    ->label('Primary')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable(),
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