<?php

namespace App\Filament\Resources\Stocks\Pages;

use App\Filament\Resources\Stocks\StockResource;
use App\Models\Material;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->successNotification(
                    fn($record) =>
                    Notification::make()
                        ->success()
                        ->title($record->type === 'add'
                            ? $record->material->name . '\'s stock has been increased by ' . $record->quantity
                            : $record->material->name . '\'s stock has been decreased by ' . $record->quantity)
                )
                ->after(function ($record) {
                    // $record is the newly created Stock model instance
                    $material = Material::find($record->material_id);

                    if ($material) {
                        if ($record->type === 'add') {
                            $material->increment('stock_quantity', $record->quantity);
                            $record->balance = $material->stock_quantity;
                            $record->save();
                        } else {
                            // Logic for 'subtract'
                            $material->decrement('stock_quantity', $record->quantity);
                            $record->balance = $material->stock_quantity;
                            $record->save();
                        }
                    }
                }),
        ];
    }
}
