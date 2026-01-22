<?php

namespace App\Filament\Resources\Encashments\Pages;

use App\Filament\Resources\Encashments\EncashmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEncashments extends ListRecords
{
    protected static string $resource = EncashmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
