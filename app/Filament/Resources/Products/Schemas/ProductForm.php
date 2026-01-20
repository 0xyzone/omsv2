<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
// v4 Schema Layout Components
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make([
                    'default' => 1,
                    'lg' => 3,
                ])
                ->columnSpanFull()
                ->schema([
                    
                    // Left Column: Primary Information (2/3)
                    Group::make([
                        Section::make('Product Details')
                            ->description('Basic information and categorization.')
                            ->icon('heroicon-m-shopping-bag')
                            ->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),

                                Textarea::make('description')
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Inventory & Pricing')
                            ->description('Manage identification and financial values.')
                            ->icon('heroicon-m-currency-dollar')
                            ->columns(2)
                            ->schema([
                                TextInput::make('sku')
                                    ->label('SKU (Stock Keeping Unit)')
                                    ->placeholder('e.g. PRD-001')
                                    ->unique(ignoreRecord: true),

                                TextInput::make('price')
                                    ->label('Selling Price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->placeholder('0.00'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                    // Right Column: Status & Media (1/3)
                    Group::make([
                        Section::make('Configuration')
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Visible in Store')
                                    ->helperText('Enable to make product purchasable.')
                                    ->default(true),

                                Toggle::make('is_customizable')
                                    ->label('Allow Customization')
                                    ->live(), // Refresh UI when toggled

                                TextInput::make('customization_base_fee')
                                    ->label('Customization Fee')
                                    ->numeric()
                                    ->prefix('$')
                                    ->hidden(fn ($get) => ! $get('is_customizable')) // Only show if toggle is ON
                                    ->placeholder('0.00'),
                            ]),

                        Section::make('Product Image')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->hiddenLabel()
                                    ->image()
                                    ->disk('public')
                                    ->directory('product-images')
                                    ->imageAspectRatio('1:1')
                                    ->automaticallyOpenImageEditorForAspectRatio()
                                    ->imageResizeMode('cover')
                                    ->panelAspectRatio('1:1')
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['1:1'])
                                    ->imagePreviewHeight('250')
                                    ->panelLayout('integrated')
                                    ->downloadable()
                                    ->moveFiles(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                ]),
            ]);
    }
}