<?php

namespace App\Filament\Resources\Stocks;

use BackedEnum;
use App\Models\Stock;
use App\Models\Material;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Stocks\Pages\EditStock;
use App\Filament\Resources\Stocks\Pages\ListStocks;
use App\Filament\Resources\Stocks\Pages\CreateStock;
use App\Filament\Resources\Stocks\Schemas\StockForm;
use App\Filament\Resources\Stocks\Tables\StocksTable;

class StockResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingUp;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::ArrowTrendingUp;
    protected static \UnitEnum|string|null $navigationGroup = 'Stock Management';
    protected static $sort = 2;

    protected static ?string $recordTitleAttribute = 'Stock';

    public static function form(Schema $schema): Schema
    {
        return StockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StocksTable::configure($table);
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
            'index' => ListStocks::route('/'),
            // 'create' => CreateStock::route('/create'),
            // 'edit' => EditStock::route('/{record}/edit'),
        ];
    }
}
