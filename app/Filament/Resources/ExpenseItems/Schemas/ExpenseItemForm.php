<?php

namespace App\Filament\Resources\ExpenseItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('expense_category_id')
                    ->relationship('expense_category', 'name')
                    ->native(false)
                    ->preload()
                    ->createOptionForm([
                        TextInput::make('name')
                        ->label("New Expense Category Name")
                    ])
                    ->required(),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
