<?php

namespace App\Filament\Resources\Stocks\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class StocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                textColumn::make('id')
                    ->searchable(),
                ImageColumn::make('image_path'),
                TextColumn::make('date')
                    ->date()
                    ->sortable(),
                TextColumn::make('material.name')
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('balance'),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'add' => 'success',
                        'subtract' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn(string $state, $record) => $record->is_damaged ? 'Damaged' : ucfirst($state))
                    ->extraAttributes([
                        'class' => 'capitalize',
                    ]),
                TextColumn::make('notes')
                    ->lineClamp(2)
                    ->tooltip(fn($record) => $record->notes),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
