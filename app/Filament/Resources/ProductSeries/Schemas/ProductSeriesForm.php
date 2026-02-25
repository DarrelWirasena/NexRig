<?php

namespace App\Filament\Resources\ProductSeries\Schemas;

use App\Helpers\CloudinaryHelper;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductSeriesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),

                FileUpload::make('banner_image_upload')
                    ->label('Banner Image')
                    ->image()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) return;

                        try {
                            $url = CloudinaryHelper::upload($state, 'product-series');
                            $set('banner_image', $url);
                        } catch (\Exception $e) {
                            //
                        }
                    })
                    ->dehydrated(false),

                TextInput::make('banner_image')
                    ->label('Banner Image URL (from Cloudinary)')
                    ->readOnly()
                    ->hidden(fn ($get) => !$get('banner_image')),
            ]);
    }
}