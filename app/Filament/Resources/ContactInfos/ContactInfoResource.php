<?php

namespace App\Filament\Resources\ContactInfos;

use App\Filament\Resources\ContactInfos\Pages\CreateContactInfo;
use App\Filament\Resources\ContactInfos\Pages\EditContactInfo;
use App\Filament\Resources\ContactInfos\Pages\ListContactInfos;
use App\Models\ContactInfo;
use BackedEnum;
use UnitEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ContactInfoResource extends Resource
{
    protected static ?string $model = ContactInfo::class;

     protected static string | UnitEnum | null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->required()
                ->options([
                    'email'    => 'Email',
                    'whatsapp' => 'WhatsApp',
                    'address'  => 'Address',
                ]),

            TextInput::make('label')
                ->required()
                ->placeholder('Email Support, WhatsApp Chat, dll...'),

            TextInput::make('title')
                ->nullable()
                ->placeholder('NexRig Experience Center (khusus address)'),

            TextInput::make('value')
                ->required()
                ->placeholder('nexrigsupp0rt@gmail.com / teks alamat...'),

            TextInput::make('url')
                ->nullable()
                ->placeholder('mailto:... / https://wa.me/... / https://maps.google.com/...'),

            TextInput::make('display_value')
                ->nullable()
                ->placeholder('Lihat di Peta / +62 895-0709-4710'),

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
            TextColumn::make('type'),
            TextColumn::make('label'),
            TextColumn::make('value'),
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
            'index'  => ListContactInfos::route('/'),
            'create' => CreateContactInfo::route('/create'),
            'edit'   => EditContactInfo::route('/{record}/edit'),
        ];
    }
}