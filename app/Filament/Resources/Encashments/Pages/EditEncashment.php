<?php

namespace App\Filament\Resources\Encashments\Pages;

use App\Filament\Resources\Encashments\EncashmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEncashment extends EditRecord
{
    protected static string $resource = EncashmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
