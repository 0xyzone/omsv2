<?php

namespace App\Filament\Taker\Resources\Orders\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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
                TextColumn::make('user.name')
                    ->label('Taker')
                    ->size('xs')
                    ->color('primary')
                    ->weight(FontWeight::Medium)
                    ->badge()
                    ->searchable()
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
                        if ($panelId === 'taker' && auth()->user()->hasRole('super_admin')) {
                            // Super admin can change
                            return false;
                        } elseif ($panelId === 'taker' && in_array($status, ['processing', 'processed', 'packing', 'completed', 'cancelled'])) {
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
                    ->prefix('रु ')
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
                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'unpaid' => 'danger',
                        default => 'warning',
                    })
                    ->getStateUsing(function ($record) {
                        $paymentsMade = $record->orderPayments()->sum('amount');
                        $finalAmount = $record->final_amount;
                        $state = ($paymentsMade > 0 && $finalAmount > $paymentsMade) ? 'partially_paid' : ($paymentsMade >= $finalAmount ? 'paid' : 'unpaid');

                        return $state; // If final amount is 0, consider it paid
                    }),
                TextColumn::make('total_paid')
                    ->label('Total Paid')
                    ->prefix('रु ')
                    ->getStateUsing(function ($record) {
                        return $record->orderPayments()->sum('amount');
                    }),
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
                Action::make('Add Payment')
                    ->icon('heroicon-m-currency-rupee')
                    ->button()
                    ->color('success')
                    ->modalWidth('4xl')
                    ->modalHeading(fn($record) => "Payments for Order #{$record->id}")
                    ->fillForm(fn($record) => [
                        'order_payments' => $record->orderPayments->toArray(),
                    ])
                    ->form([
                        Repeater::make('order_payments')
                            ->relationship('orderPayments')
                            ->label('Payment History')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('amount')
                                            ->label('Amount')
                                            ->prefix('रु. ')
                                            ->numeric()
                                            ->required(),
                                        Select::make('payment_method')
                                            ->required()
                                            ->disablePlaceholderSelection()
                                            ->options([
                                                'cash' => 'Cash',
                                                'card' => 'Card',
                                                'online' => 'Online',
                                                'other' => 'Other',
                                            ])
                                            ->default('cash'),
                                    ]),
                                Grid::make(2)
                                    ->schema([
                                        \Filament\Forms\Components\DatePicker::make('payment_date')
                                            ->label('Date')
                                            ->required()
                                            ->native(false)
                                            ->default(now()),
                                        TextInput::make('transaction_id')
                                            ->label('Transaction ID/Ref'),
                                    ]),
                                Textarea::make('notes')
                                    ->rows(2),
                                FileUpload::make('images')
                                    ->multiple()
                                    ->image()
                                    ->directory('order_payment_receipts')
                                    ->panelLayout('grid'),
                            ])
                            ->collapsible()
                            ->defaultItems(0)
                            ->addActionLabel('Add New Payment'),
                    ])
                    ->action(function () {
                        \Filament\Notifications\Notification::make()
                            ->title('Payments Updated')
                            ->success()
                            ->send();
                    })
                    ->visible(
                        function ($record) {
                            $panel = Filament::getCurrentPanel()?->getId();
                            $paymentsMade = $record->orderPayments()->sum('amount');
                            $finalAmount = $record->final_amount;
                            $paymentStatus = ($paymentsMade > 0 && $finalAmount > $paymentsMade) ? 'partially_paid' : ($paymentsMade >= $finalAmount ? 'paid' : 'unpaid');
                            return $panel === 'taker' && $paymentStatus !== 'paid';
                        }
                    ),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}