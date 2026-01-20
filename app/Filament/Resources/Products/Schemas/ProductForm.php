<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('product-images')
                    ->imageAspectRatio('1:1')
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->imageResizeMode('cover')
                    ->panelAspectRatio('1:1')
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions([
                        '1:1'
                    ])
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadProgressIndicatorPosition('bottom')
                    ->downloadable()
                    ->moveFiles(),
                TextInput::make('sku')
                    ->label('SKU'),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_customizable')
                    ->required(),
                TextInput::make('customization_base_fee')
                    ->numeric(),
            ]);
    }
}
