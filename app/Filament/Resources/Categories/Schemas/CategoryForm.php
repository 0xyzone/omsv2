<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image()
                    ->disk('public')
                    ->directory('category-images')
                    ->imageAspectRatio('1:1')
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->imageResizeMode('cover')
                    ->panelAspectRatio('1:1')
                    ->imageEditor()
                    ->imageEditorAspectRatioOptions([
                        '1:1',
                    ])
                    ->imagePreviewHeight('250')
                    ->loadingIndicatorPosition('left')
                    ->panelAspectRatio('1:1')
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    // ->uploadButtonPosition('left')
                    ->uploadProgressIndicatorPosition('bottom')
                    ->downloadable()
                    ->moveFiles()
                    ->rule(Rule::dimensions()->minWidth(1080)->minHeight(1080)),
            ]);
    }
}
