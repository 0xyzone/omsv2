<?php

namespace App\Filament\Resources\ExpenseItems;

use App\Filament\Resources\ExpenseItems\Pages\CreateExpenseItem;
use App\Filament\Resources\ExpenseItems\Pages\EditExpenseItem;
use App\Filament\Resources\ExpenseItems\Pages\ListExpenseItems;
use App\Filament\Resources\ExpenseItems\Schemas\ExpenseItemForm;
use App\Filament\Resources\ExpenseItems\Tables\ExpenseItemsTable;
use App\Models\ExpenseItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExpenseItemResource extends Resource
{
    protected static ?string $model = ExpenseItem::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Expense Management';

    protected static ?string $recordTitleAttribute = 'ExpenseItem';

    public static function form(Schema $schema): Schema
    {
        return ExpenseItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpenseItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenseItems::route('/'),
            // 'create' => CreateExpenseItem::route('/create'),
            // 'edit' => EditExpenseItem::route('/{record}/edit'),
        ];
    }
}
