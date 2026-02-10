<?php

namespace App\Filament\Resources\ExpenseRecords\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ExpenseRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id()),
                Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'under-revision' => 'Under Revision',
                        'finalized' => 'Finalized'
                    ])
                    ->default('pending'),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                Select::make('discount_type')
                    ->options([
                        'none' => 'None',
                        'percentage' => 'Percentage (%)',
                        'amount' => 'Amount'
                    ])
                    ->default('none')
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::getFinals($get, $set);
                    }),
                TextInput::make('discount_value')
                    ->numeric()
                    ->live(onBlur: true)
                    ->default(0.0)
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        static::getFinals($get, $set);
                    })
                    ->hidden(fn(Get $get) => $get('discount_type') === 'none'),
                TextInput::make('discount_amount')
                    ->numeric()
                    ->default(0.0)
                    ->hidden(fn(Get $get) => $get('discount_type') === 'none')
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('final_amount')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                Repeater::make('expense_record_items')
                    ->relationship()
                    ->columnSpanFull()
                    ->columns(5)
                    ->schema([
                        Select::make('expense_item_id')
                            ->relationship('expense_item', 'name')
                            ->live()
                            ->native(false)
                            ->preload()
                            ->columnSpan(2)
                            ->createOptionForm([
                                Select::make('expense_category_id')
                                    ->relationship('expense_category', 'name'),
                                TextInput::make('name')
                            ]),
                        TextInput::make('quantity')
                            ->disabled(fn(Get $get) => $get('expense_item_id') === null)
                            ->live(onBlur: true)
                            ->numeric()
                            ->default(1)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $price = $get('price');
                                $total = $price * $state;

                                $set('total', $total);
                                static::getTotals($get, $set);
                            }),
                        TextInput::make('price')
                            ->disabled(fn(Get $get) => $get('expense_item_id') === null)
                            ->numeric()
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $quantity = $get('quantity');
                                $total = $state * $quantity;

                                $set('total', $total);
                                static::getTotals($get, $set);
                            }),
                        TextInput::make('total')
                            ->disabled()
                            ->dehydrated()
                            ->prefix('रु. ')
                    ])
            ]);
    }

    protected static function getTotals($get, $set)
    {
        $totalAmount = 0;
        $repeaterItems = $get('../../expense_record_items') ?? $get('expense_record_items');
        foreach ($repeaterItems as $item) {
            $totalAmount += $item['total'];
        }

        $discountAmount = $get('../../discount_amount');

        $discountType = $get('../../discount_type');
        $discountValue = $get('../../discount_value');

        if ($discountType === 'percentage') {
            $discountAmount = $totalAmount * ($discountValue / 100);
        } else {
            $discountAmount = $discountValue;
        }
        $set('../../discount_amount', $discountAmount);

        $finalAmount = $totalAmount - $discountAmount;
        $set('../../final_amount', $finalAmount);

        $set('../../total_amount', $totalAmount);
    }

    protected static function getFinals($get, $set)
    {
        $discountValue = $get('discount_value');
        $totalAmount = $get('total_amount');
        $discountType = $get('discount_type');
        $discountAmount = 0;

        if ($discountType === "percentage") {
            $discountAmount = $totalAmount * ($discountValue / 100);
        } else {
            $discountAmount = $discountValue;
        }

        $set('discount_amount', $discountAmount);

        $finalAmount = $totalAmount - $discountAmount;
        $set('final_amount', $finalAmount);
    }
}
