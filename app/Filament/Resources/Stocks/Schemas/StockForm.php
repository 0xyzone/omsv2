<?php

namespace App\Filament\Resources\Stocks\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
// Use v4 Schema components
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

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
                                    ->columnSpanFull(),

                                Select::make('type')
                                    ->label('Transaction Type')
                                    ->options([
                                        'add' => 'Stock In (Add)',
                                        'subtract' => 'Stock Out (Subtract)',
                                    ])
                                    ->default('add')
                                    ->required()
                                    ->selectablePlaceholder(false)
                                    ->native(false), // Better UI look

                                TextInput::make('quantity')
                                    ->label('Quantity Amount')
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
                                    ->directory('stock-movements'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                ]),
            ]);
    }
}