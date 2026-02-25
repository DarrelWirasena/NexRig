<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Helpers\CloudinaryHelper;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('category')
                    ->required()
                    ->default('General'),

                FileUpload::make('image_upload')
                    ->label('Article Image')
                    ->image()
                    ->live()
                    ->afterStateUpdated(function ($state, Set $set) {
                        if (!$state) return;

                        try {
                            $url = CloudinaryHelper::upload($state, 'articles');
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

                Textarea::make('excerpt')
                    ->columnSpanFull(),
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('author')
                    ->required()
                    ->default('NexRig Admin'),
                TextInput::make('reading_time')
                    ->required()
                    ->numeric()
                    ->default(5),
                TextInput::make('tags'),
                TextInput::make('meta_title'),
                TextInput::make('meta_description'),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'published' => 'Published'])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('published_at'),
            ]);
    }
}