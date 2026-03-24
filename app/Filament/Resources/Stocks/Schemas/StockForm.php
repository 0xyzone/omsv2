<?php

namespace App\Filament\Resources\Stocks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class StockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                    ->columnSpanFull() // Keeps the grid contained as per your preference
                    ->schema([

                        // Left Column: Transaction Details (2/3 width)
                        Group::make([
                            Section::make('Stock Movement')
                                ->description('Record whether you are adding or removing inventory.')
                                ->icon('heroicon-m-arrows-right-left')
                                ->columns(2)
                                ->schema([
                                    Select::make('material_id')
                                        ->relationship('material', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpan(function (Get $get) {
                                            return $get('type') === 'subtract' ? 1 : 2;
                                        }),

                                    Radio::make('is_damaged')
                                        ->label('Is Damaged?')
                                        ->inline(condition: true)
                                        ->options([
                                            'true' => 'Yes',
                                            'false' => 'No',
                                        ])
                                        ->default("false")
                                        ->visible(function (Get $get) {
                                            return $get('type') === 'subtract';
                                        })
                                        ->columnSpan(1),

                                    Select::make('type')
                                        ->label('Transaction Type')
                                        ->disabledOn('edit')
                                        ->options([
                                            'add' => 'Stock In (Add)',
                                            'subtract' => 'Stock Out (Subtract)',
                                        ])
                                        ->live()
                                        ->default('add')
                                        ->required()
                                        ->selectablePlaceholder(false)
                                        ->native(false), // Better UI look

                                    TextInput::make('quantity')
                                        ->label('Quantity Amount')
                                        ->disabledOn('edit')
                                        ->required()
                                        ->numeric()
                                        ->minValue(1)
                                        ->prefixIcon('heroicon-m-hashtag'),

                                    DatePicker::make('date')
                                        ->label('Transaction Date')
                                        ->default(now())
                                        ->required()
                                        ->columnSpanFull(),
                                ]),

                            Section::make('Additional Information')
                                ->schema([
                                    Textarea::make('notes')
                                        ->placeholder('Reason for adjustment, purchase order number, etc...')
                                        ->rows(3),
                                ])
                                ->collapsible(),
                        ])
                            ->columnSpan(['lg' => 2]),

                        // Right Column: Proof/Media (1/3 width)
                        Group::make([
                            Section::make('Documentation')
                                ->description('Upload receipts or photos of the stock.')
                                ->schema([
                                    FileUpload::make('image_path')
                                        ->label('Reference Image')
                                        ->image()
                                        ->imageEditor()
                                        ->directory('stock-movements')
                                        ->disk('public')
                                        ->visibility('public'),
                                ]),
                        ])
                            ->columnSpan(['lg' => 1]),
                        TextInput::make('balance')
                            ->hidden()
                            ->default(function (Model $record = null, array $data = []) {
                                $materialId = $data['material_id'] ?? $record?->material_id;
                                $quantity = $data['quantity'] ?? $record?->quantity;
                                $type = $data['type'] ?? $record?->type;
                                // Calculate balance based on type and quantity
                                if ($type === 'add') {
                                    return $quantity;
                                } elseif ($type === 'subtract') {
                                    return -$quantity;
                                }
                                return 0;
                            })
                    ])
            ]);
    }
}