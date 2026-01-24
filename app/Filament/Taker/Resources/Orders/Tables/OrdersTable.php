<?php

namespace App\Filament\Taker\Resources\Orders\Tables;

use Filament\Tables\Table;
use Filament\Facades\Filament;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->poll("10s")
            ->modifyQueryUsing(function (Builder $query) {
                if (Filament::getCurrentPanel()?->getId() === 'maker') {
                    return $query->whereIn('status', ['confirmed', 'processing']);
                }

                return $query;
            })
            ->columns([
                Split::make([
                    // Cluster 1: Customer & Taker info
                    Stack::make([
                        TextColumn::make('customer_name')
                            ->weight(FontWeight::Bold)
                            ->searchable(),
                        TextColumn::make('customer_phone')
                            ->size('xs')
                            ->color('gray')
                            ->icon('heroicon-m-phone')
                            ->searchable(),
                        TextColumn::make('user.name')
                            ->label('Taker')
                            ->size('xs')
                            ->color('primary')
                            ->weight(FontWeight::Medium)
                            ->formatStateUsing(fn($state) => "Taker: {$state}"),
                    ])->space(1),

                    // Cluster 2: Status & Timeline
                    Stack::make([
                        SelectColumn::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'processed' => 'Processed',
                                'packing' => 'Packing',
                                'packed' => 'Packed',
                                'out_for_delivery' => 'Out for Delivery',
                                'returning' => 'Returning',
                                'returned' => 'Returned',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ]),
                        TextColumn::make('created_at')
                            ->dateTime('M j, Y H:i')
                            ->size('xs')
                            ->color('gray'),
                    ])->space(1),

                    // Cluster 3: Financials
                    Stack::make([
                        TextColumn::make('final_amount')
                            ->weight(FontWeight::Bold)
                            ->alignEnd()
                            ->money('NPR') // Or your local currency code
                            ->color('success'),
                        TextColumn::make('total_amount')
                            ->label('Items Subtotal')
                            ->size('xs')
                            ->color('gray')
                            ->alignEnd()
                            ->formatStateUsing(fn($state) => "Base: Rs. " . number_format($state, 2)),
                    ])->space(1),
                ]),

                // Keep detailed info toggleable for a clean look
                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->money('NPR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('customization_amount')
                    ->label('Customization')
                    ->money('NPR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('customer_address')
                    ->label('Address')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->contentGrid([
                'md' => 1,
                'xl' => 2,
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton()->color('warning')
                    ->visible(fn() => filament()->getCurrentPanel()?->getId() !== 'maker'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}