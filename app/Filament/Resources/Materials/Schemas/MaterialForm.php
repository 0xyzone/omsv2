<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
// Use these Schema imports
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3, // 3 Columns total
                ])
                ->columnSpanFull()
                ->schema([
                    // Main Content (Left) - Spans 2 columns
                    Group::make([
                        Section::make('General Information')
                            ->description('Identify and describe this material.')
                            ->icon('heroicon-m-document-text')
                            ->schema([
                                TextInput::make('name')
                                    ->required(),
                                Textarea::make('description')
                                    ->rows(4),
                            ]),

                        Section::make('Inventory & Pricing')
                            ->icon('heroicon-m-banknotes')
                            ->columns(2)
                            ->schema([
                                TextInput::make('stock_quantity')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->disabledOn('edit')
                                    ->prefixIcon('heroicon-m-cube'),
                                TextInput::make('unit_of_measure')
                                    ->prefixIcon('heroicon-m-beaker'),
                                // TextInput::make('cost_per_unit')
                                //     ->numeric()
                                //     ->prefix('Rs.')
                                //     ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                    // Sidebar Content (Right) - Spans 1 column
                    Group::make([
                        Section::make('Status')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Available for use')
                                    ->default(true),
                            ]),

                        Section::make('Visuals')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->image()
                                    ->imageEditor()
                                    ->directory('materials'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                ]),
            ]);
    }
}