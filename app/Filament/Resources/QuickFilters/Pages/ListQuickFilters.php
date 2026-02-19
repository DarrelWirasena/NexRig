<?php

namespace App\Filament\Resources\QuickFilters\Pages;

use App\Filament\Resources\QuickFilters\QuickFilterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuickFilters extends ListRecords
{
    protected static string $resource = QuickFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
