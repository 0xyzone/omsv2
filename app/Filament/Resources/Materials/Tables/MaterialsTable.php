<?php

namespace App\Filament\Resources\Materials\Tables;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Image')
                    ->imageGallery()
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('stock_quantity')
                    ->label('Current Stock')
                    ->numeric()
                    ->getStateUsing(fn($record) => $record->stock_quantity . ' ' . $record->unit_of_measure),
                    TextColumn::make('damaged_stock')
                    ->label('Damaged Stock')
                    ->numeric()
                    ->getStateUsing(function ($record) {
                        $damagedStock = $record->stocks()
                            ->where('is_damaged', 'true')
                            ->sum('quantity');
                        return $damagedStock . ' ' . $record->unit_of_measure;
                    }),
                // TextColumn::make('unit_of_measure')
                //     ->searchable(),
                // TextColumn::make('cost_per_unit')
                //     ->numeric()
                //     ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
