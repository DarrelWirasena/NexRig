<?php

namespace App\Filament\Resources\QuickFilters\Pages;

use App\Filament\Resources\QuickFilters\QuickFilterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQuickFilter extends EditRecord
{
    protected static string $resource = QuickFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
