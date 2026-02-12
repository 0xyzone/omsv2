<?php

namespace App\Filament\Taker\Resources\Orders\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->poll("5s")
            ->modifyQueryUsing(function (Builder $query) {
                if (Filament::getCurrentPanel()?->getId() === 'maker') {
                    return $query->whereIn('status', ['confirmed', 'processing']);
                } elseif (Filament::getCurrentPanel()?->getId() === 'packer') {
                    return $query->whereIn('status', ['processed', 'packing']);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->alignCenter()
                    ->searchable(),
                BadgeColumn::make('user.name')
                    ->label('Taker')
                    ->size('xs')
                    ->color('primary')
                    ->weight(FontWeight::Medium)
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                TextColumn::make('customer_name')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                TextColumn::make('customer_phone')
                    ->size('xs')
                    ->color('gray')
                    ->icon('heroicon-m-phone')
                    ->searchable()
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                TextColumn::make('customer_address')
                    ->label('Address')
                    ->limit(30)
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                SelectColumn::make('status')
                    ->options(function () {
                        $panelId = Filament::getCurrentPanel()?->getId();

                        // Options for the Maker panel
                        if ($panelId === 'maker') {
                            return [
                                'confirmed' => 'Confirmed',
                                'processing' => 'Processing',
                                'processed' => 'Processed',
                            ];
                        }

                        // Options for the Packer panel
                        if ($panelId === 'packer') {
                            return [
                                'processed' => 'Processed',
                                'packing' => 'Packing',
                                'packed' => 'Packed',
                            ];
                        }

                        // Default options for Taker or other panels
                        return [
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
                        ];
                    })
                    ->disabled(function ($record) {
                        $panelId = Filament::getCurrentPanel()?->getId();
                        $status = $record->status;
                        if ($panelId === 'taker' && in_array($status, ['processing', 'processed', 'packing',])) {
                            return true;
                        }

                        return false;
                    })
                    ->disableOptionWhen(function (string $value): bool {
                        $panelId = Filament::getCurrentPanel()?->getId();

                        if ($panelId === 'taker') {
                            // Disable everything EXCEPT processing and processed
                            return in_array($value, ['processing', 'processed', 'packing', 'packed']);
                        }

                        return false; // Taker can select anything
                    }),
                TextColumn::make('final_amount')
                    ->weight(FontWeight::Bold)
                    ->alignEnd()
                    ->money('NPR') // Or your local currency code
                    ->color('success')
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                TextColumn::make('total_amount')
                    ->label('Items Subtotal')
                    ->prefix('रु ')
                    ->size('xs')
                    ->color('gray')
                    ->alignEnd()
                    ->formatStateUsing(fn($state) => "Base: Rs. " . number_format($state, 2))
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),

                // Keep detailed info toggleable for a clean look
                TextColumn::make('discount_amount')
                    ->label('Discount')
                    ->prefix('रु ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                TextColumn::make('customization_amount')
                    ->label('Customization')
                    ->prefix('रु ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
                TextColumn::make('created_at')
                    ->dateTime('M j, Y H:i')
                    ->size('xs')
                    ->color('gray')
                    ->hidden(fn() => Filament::getCurrentPanel()?->getId() === 'packer' || Filament::getCurrentPanel()?->getId() === 'maker'),
            ])
            // ->contentGrid([
            //     'md' => 1,
            //     'xl' => 2,
            // ])
            ->striped(true)
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton()->color('warning')
                    ->visible(fn($record) => filament()->getCurrentPanel()?->getId() === 'taker' && ($record->status === 'pending' || $record->status === 'confirmed')),
                Action::make('Print')
                ->button()
                ->color('primary')
                ->url(function ($record) {
                    return route('print', ['id' => $record]);
                }, shouldOpenInNewTab: true),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}