<?php

namespace App\Filament\Resources\Games\Schemas;

use App\Helpers\CloudinaryHelper;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class GameForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                FileUpload::make('image_upload')
                    ->image()
                    ->label('Game Image')
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) return;

                        try {
                            $url = CloudinaryHelper::upload($state, 'games');
                            $set('image_url', $url);
                        } catch (\Exception $e) {
                            // handle error jika perlu
                        }
                    })
                    ->dehydrated(false),

                TextInput::make('image_url')
                    ->label('Image URL (from Cloudinary)')
                    ->readOnly()
                    ->hidden(fn ($get) => !$get('image_url')),
            ]);
    }
}