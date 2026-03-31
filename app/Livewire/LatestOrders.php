<?php

namespace App\Livewire;

use App\Models\Order;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends TableWidget
{
    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(fn(): Builder => Order::query()->latest())
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('customer_name')
                    ->searchable(),
                TextColumn::make('customer_phone'),
                TextColumn::make('customer_alt_phone'),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->numeric(),
                TextColumn::make('customization_amount')
                    ->numeric(),
                TextColumn::make('discount_type')
                    ->searchable(),
                TextColumn::make('discount_value')
                    ->numeric(),
                TextColumn::make('discount_amount')
                    ->numeric(),
                TextColumn::make('delivery_charge')
                    ->numeric(),
                TextColumn::make('final_amount')
                    ->numeric(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
