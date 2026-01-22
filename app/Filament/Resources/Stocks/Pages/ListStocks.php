<?php

namespace App\Filament\Resources\Stocks\Pages;

use App\Models\Material;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Stocks\StockResource;

class ListStocks extends ListRecords
{
    protected static string $resource = StockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
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
