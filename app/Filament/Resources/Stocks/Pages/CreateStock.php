<?php

namespace App\Filament\Resources\Stocks\Pages;

use App\Filament\Resources\Stocks\StockResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;
    protected function getCreatedNotification(): ?Notification
    {
        $action = $this->record->type === 'add' ? 'added' : 'subtracted';

        return Notification::make()
            ->success()
            ->title("Stock has been {$action}");
    }
}
