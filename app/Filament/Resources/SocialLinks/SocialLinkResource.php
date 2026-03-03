<?php

namespace App\Filament\Resources\SocialLinks;

use App\Filament\Resources\SocialLinks\Pages\CreateSocialLink;
use App\Filament\Resources\SocialLinks\Pages\EditSocialLink;
use App\Filament\Resources\SocialLinks\Pages\ListSocialLinks;
use App\Models\SocialLink;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SocialLinkResource extends Resource
{
    protected static ?string $model = SocialLink::class;

     protected static string | UnitEnum | null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'platform';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('platform')
                ->required()
                ->placeholder('Instagram, Twitter, Youtube...'),

            TextInput::make('url')
                ->required()
                ->url()
                ->placeholder('https://...'),

            Toggle::make('is_active')
                ->default(true),

            TextInput::make('order')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('platform'),
            TextColumn::make('url'),
            ToggleColumn::make('is_active'),
            TextColumn::make('order')->sortable(),
        ])
        ->defaultSort('order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSocialLinks::route('/'),
            'create' => CreateSocialLink::route('/create'),
            'edit'   => EditSocialLink::route('/{record}/edit'),
        ];
    }
}