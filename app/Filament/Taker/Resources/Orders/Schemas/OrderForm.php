<?php

namespace App\Filament\Taker\Resources\Orders\Schemas;

use Filament\Panel;
use App\Models\Product;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Split;
// Correct Layout Imports for Filament 4.x
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id())
                    ->required(),
                Grid::make(2)
                    ->schema([
                    Group::make([
                        Section::make('Order Status')
                            ->hiddenOn('create')
                            ->schema([
                                Select::make('status')
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
                                    ])
                                    ->required()
                                    ->default('pending')
                                    ->native(false),
                            ]),

                        Section::make('Customer Information')
                            ->description('Basic contact and delivery details.')
                            ->icon('heroicon-m-user-circle')
                            ->columns(2)
                            ->schema([
                                TextInput::make('customer_name')
                                    ->required()
                                    ->placeholder('Full Name')
                                    ->columnSpanFull(),
                                TextInput::make('customer_phone')
                                    ->label('Primary Phone')
                                    ->tel()
                                    ->required()
                                    ->numeric()
                                    ->prefixIcon('heroicon-m-phone'),
                                TextInput::make('customer_alt_phone')
                                    ->label('Secondary Phone')
                                    ->tel()
                                    ->numeric()
                                    ->prefixIcon('heroicon-m-phone-arrow-up-right'),
                                Textarea::make('customer_address')
                                    ->label('Delivery Address')
                                    ->required()
                                    ->autosize()
                                    ->columnSpanFull()
                                    ->rows(3),
                            ]),
                    ])
                    ->columnSpan(2),

                    Section::make('Financial Summary')
                        ->icon('heroicon-m-calculator')
                        ->columns(1)
                        ->schema([
                            TextInput::make('total_amount')
                                ->label('Items Total')
                                ->prefix('रु. ')
                                ->dehydrated()
                                ->disabled(),

                            TextInput::make('customization_amount')
                                ->label('Customization Fee')
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->prefix('रु. ')
                                ->default(0.0)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, Get $get) => static::calculateFinalAmount($get, $set)),

                            Select::make('discount_type')
                                ->options([
                                    'percentage' => 'Percentage (%)',
                                    'fixed' => 'Fixed Amount',
                                    'none' => 'No Discount',
                                ])
                                ->disablePlaceholderSelection()
                                ->live()
                                ->default('none')
                                ->native(false)
                                ->afterStateUpdated(fn(Set $set, Get $get) => static::calculateFinalAmount($get, $set)),

                            TextInput::make('discount_value')
                                ->visible(fn(Get $get) => $get('discount_type') !== 'none')
                                ->numeric()
                                ->label(fn(Get $get) => $get('discount_type') === 'percentage' ? 'Rate (%)' : 'Amount')
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, Get $get) => static::calculateFinalAmount($get, $set)),

                            TextInput::make('discount_amount')
                                ->label('Discount Applied')
                                ->prefix('रु. ')
                                ->dehydrated()
                                ->disabled()
                                ->numeric(),

                            TextInput::make('final_amount')
                                ->label('Grand Total')
                                ->prefix('रु. ')
                                ->extraInputAttributes(['style' => 'font-weight: bold; font-size: 1.2rem;'])
                                ->dehydrated()
                                ->disabled()
                                ->required(),
                        ]),
                ])
                    ->grow(false)
                    ->columnSpanFull()
                    ->columns(3)
                    ->hidden(function () {
                        return Filament::getCurrentPanel()?->getId() === 'maker' || Filament::getCurrentPanel()?->getId() === 'packer';
                    }),
                // Main Content Area (Left/Center)
                Grid::make(1)
                    ->schema([
                        Section::make('Order Items')
                            ->description('Add products and manage customizations.')
                            ->icon('heroicon-m-shopping-cart')
                            ->schema([
                                Repeater::make('order_items')
                                    ->relationship('orderItems')
                                    ->afterStateUpdated(fn(Set $set, Get $get) => static::calculateFinalAmount($get, $set))
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('product_id')
                                                    ->relationship('product', 'name')
                                                    ->searchable()
                                                    ->preload()
                                                    ->reactive()
                                                    ->required()
                                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                        $product = Product::find($state);
                                                        if ($product) {
                                                            $set('price', $product->price);
                                                            $set('total', $product->price * ($get('quantity') ?? 0));
                                                        } else {
                                                            $set('price', 0);
                                                        }
                                                    }),
                                                TextInput::make('quantity')
                                                    ->hidden(fn(Get $get) => $get('product_id') == null)
                                                    ->required()
                                                    ->numeric()
                                                    ->live(onBlur: true)
                                                    ->default(1)
                                                    ->prefixIcon('heroicon-m-hashtag')
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        $price = $get('price') ?? 0; // Ensure price is loaded
                                                        $quantity = $get('quantity') ?? 0;
                                                        $set('total', $price * $quantity);
                                                        static::calculateFinalAmount($get, $set);
                                                    }),
                                                TextInput::make('total')
                                                    ->hidden(fn(Get $get) => $get('product_id') == null)
                                                    ->label('Sub-total')
                                                    ->prefix('रु. ')
                                                    ->dehydrated()
                                                    ->disabled(),
                                            ]),

                                        Grid::make(2)
                                            ->hidden(fn(Get $get) => $get('product_id') == null)
                                            ->schema([
                                                Select::make('is_customizable')
                                                    ->label('Customizable?')
                                                    ->options([
                                                        TRUE => 'Yes',
                                                        FALSE => 'No'
                                                    ])
                                                    ->required()
                                                    ->default(FALSE)
                                                    ->live()
                                                    ->disablePlaceholderSelection()
                                                    ->native(false),
                                                TextInput::make('customization_rate')
                                                    ->label('Customization Rate')
                                                    ->prefix('रु. ')
                                                    ->numeric()
                                                    ->live(onBlur: true)
                                                    ->default(0)
                                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                                        static::calculateFinalAmount($get, $set);
                                                    })
                                                    ->hidden(fn(Get $get) => $get('is_customizable') == FALSE),
                                            ]),

                                        Group::make([
                                            Textarea::make('customization_details')
                                                ->placeholder('Specific instructions for this item...')
                                                ->autosize(),
                                            FileUpload::make('images')
                                                ->multiple()
                                                ->image()
                                                ->imageEditor()
                                                ->directory('order_item_customizations')
                                                ->panelLayout('integrated'),
                                        ])->hidden(fn(Get $get) => $get('is_customizable') == FALSE),

                                        Textarea::make('notes')
                                            ->label('Item Notes')
                                            ->placeholder('Internal notes for this product...')
                                            ->rows(2),

                                        Hidden::make('price')->dehydrated(),
                                    ])
                                    ->collapsible()
                                    ->cloneable()
                                    ->itemLabel(fn(array $state): ?string => Product::find($state['product_id']) ? Product::find($state['product_id'])->name . ' - रु.' . $state['price'] : 'New Item')
                                    ->columnSpanFull(),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->grow(true)
                    ->columnSpanFull(),

                // Sidebar Area (Right)
            ]);
    }

    protected static function calculateFinalAmount(Get $get, Set $set): void
    {
        $repeaterItems = $get('order_items') ?? $get('../../order_items') ?? [];
        $totalAmount = 0;
        $totalCustomizationRate = 0;

        foreach ($repeaterItems as $item) {
            $prodectId = $item['product_id'] ?? null;
            $price = Product::find($prodectId)?->price ?? 0;
            $quantity = (float) ($item['quantity'] ?? 0);
            $customizationRate = (float) ($item['customization_rate'] ?? 0);
            $totalAmount += $price * $quantity;
            $totalCustomizationRate += ($quantity * $customizationRate) ?? 0;
        }
        $customizationAmount = $totalCustomizationRate;
        $discountType = $get('discount_type') ?? 'none';
        $discountValue = (float) ($get('discount_value') ?? 0);
        $discountAmount = 0;
        if ($discountType === 'percentage' && $discountValue > 0) {
            $discountAmount = ($totalAmount + $customizationAmount) * ($discountValue / 100);
        } elseif ($discountType === 'fixed' && $discountValue > 0) {
            $discountAmount = $discountValue;
        }
        $set('discount_amount', $discountAmount);
        $set('total_amount', $totalAmount);
        $set('customization_amount', $customizationAmount);

        $finalAmount = $totalAmount + $customizationAmount - $discountAmount;
        $set('final_amount', $finalAmount >= 0 ? $finalAmount : 0);
    }
}