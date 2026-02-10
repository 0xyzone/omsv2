<?php

namespace App\Filament\Resources\ExpenseRecords;

use App\Filament\Resources\ExpenseRecords\Pages\CreateExpenseRecord;
use App\Filament\Resources\ExpenseRecords\Pages\EditExpenseRecord;
use App\Filament\Resources\ExpenseRecords\Pages\ListExpenseRecords;
use App\Filament\Resources\ExpenseRecords\Schemas\ExpenseRecordForm;
use App\Filament\Resources\ExpenseRecords\Tables\ExpenseRecordsTable;
use App\Models\ExpenseRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExpenseRecordResource extends Resource
{
    protected static ?string $model = ExpenseRecord::class;
    protected static \UnitEnum|string|null $navigationGroup = 'Expense Management';

    protected static ?string $recordTitleAttribute = 'ExpenseRecord';

    public static function form(Schema $schema): Schema
    {
        return ExpenseRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpenseRecordsTable::configure($table);
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
            'index' => ListExpenseRecords::route('/'),
            // 'create' => CreateExpenseRecord::route('/create'),
            // 'edit' => EditExpenseRecord::route('/{record}/edit'),
        ];
    }
}
