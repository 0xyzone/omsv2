<?php

namespace App\Filament\Resources\Stocks\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
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
                        default => 'gray',
                    })
                    ->getStateUsing(fn($record) => $record->type === 'subtract' && $record->is_damaged === 'true' ? 'damaged' : $record->type)
                    ->formatStateUsing(fn(string $state, $record) => $record->type === 'subtract' && $record->is_damaged === 'true' ? 'Damaged' : ($record->type === 'add' ? 'Stock-In' : ($record->type === 'subtract' ? 'Stock-Out' : ucfirst($state))))
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
                Action::make('delete')
                    ->label('Delete')
                    ->action(function ($record) {
                        $material = $record->material;
                        if ($record->type === 'add') {
                            $material->decrement('stock_quantity', $record->quantity);
                        } elseif ($record->type === 'subtract') {
                            $material->increment('stock_quantity', $record->quantity);
                        }
                        $record->delete();
                        Notification::make()
                            ->title('Stock record deleted')
                            ->body('The stock record has been successfully deleted and the material quantity has been adjusted accordingly.')
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-m-trash')
                    ->requiresConfirmation()
                    ->color('danger'),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
