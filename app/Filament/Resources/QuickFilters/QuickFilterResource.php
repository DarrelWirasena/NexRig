<?php

namespace App\Filament\Resources\QuickFilters;

use App\Filament\Resources\QuickFilters\Pages\CreateQuickFilter;
use App\Filament\Resources\QuickFilters\Pages\EditQuickFilter;
use App\Filament\Resources\QuickFilters\Pages\ListQuickFilters;
use App\Filament\Resources\QuickFilters\Schemas\QuickFilterForm;
use App\Filament\Resources\QuickFilters\Tables\QuickFiltersTable;
use App\Models\QuickFilter;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuickFilterResource extends Resource
{
    protected static ?string $model = QuickFilter::class;

    // 1. Ubah icon-nya agar tidak kembar dengan menu lain (misal pakai icon Sparkles atau Funnel)
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    // 2. TAMBAHKAN BARIS INI: Untuk memasukkannya ke dropdown "Catalog"
    protected static string | UnitEnum | null $navigationGroup = 'Catalog';

    // 3. TAMBAHKAN BARIS INI (Opsional): Untuk mengatur urutan menu di dalam dropdown Catalog
    // Jika Products ada di urutan 1, Categories 2, dsb, kasih angka lebih besar agar dia ada di bawah.
    protected static ?int $navigationSort = 5;
    
    protected static ?string $recordTitleAttribute = 'keywoard';

    public static function form(Schema $schema): Schema
    {
        return QuickFilterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuickFiltersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuickFilters::route('/'),
            'create' => CreateQuickFilter::route('/create'),
            'edit' => EditQuickFilter::route('/{record}/edit'),
        ];
    }
}
