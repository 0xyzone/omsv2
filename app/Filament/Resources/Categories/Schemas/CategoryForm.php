<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class CategoryForm
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
                    // Main Content (Left)
                    Group::make([
                        Section::make('Category Details')
                            ->description('Basic identification for this category group.')
                            ->icon('heroicon-m-tag')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g., Bags, Accessories, Electronics'),

                                Textarea::make('description')
                                    ->rows(5)
                                    ->placeholder('Provide a brief overview of what this category includes...'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                    // Sidebar (Right)
                    Group::make([
                        Section::make('Category Image')
                            ->description('High-resolution square thumbnail.')
                            ->schema([
                                FileUpload::make('image_path')
                                    ->hiddenLabel() // Label is already in Section title
                                    ->image()
                                    ->disk('public')
                                    ->directory('category-images')
                                    ->imageAspectRatio('1:1')
                                    ->automaticallyOpenImageEditorForAspectRatio()
                                    ->imageResizeMode('cover')
                                    ->imageEditor()
                                    ->imageEditorAspectRatioOptions(['1:1'])
                                    ->imagePreviewHeight('250')
                                    ->panelLayout('integrated')
                                    ->downloadable()
                                    ->moveFiles()
                                    // Keeping your high-quality requirement
                                    ->rule(Rule::dimensions()->minWidth(1080)->minHeight(1080))
                                    ->helperText('Minimum size: 1080x1080px (1:1 Ratio)'),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
                ]),
            ]);
    }
}