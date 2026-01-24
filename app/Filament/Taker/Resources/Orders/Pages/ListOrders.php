<?php

namespace App\Filament\Taker\Resources\Orders\Pages;

use App\Filament\Taker\Resources\Orders\OrderResource;
use Filament\Actions\CreateAction;
use Filament\Panel;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
            ->visible(fn () => filament()->getCurrentPanel()?->getId() !== 'maker')
            // ->after(function ($record) {
            //     dd($record);
            //     $record->user_id = auth()->id();
            //     $record->save();
            // }),
        ];
    }
}
