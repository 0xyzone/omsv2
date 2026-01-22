<?php

namespace App\Filament\Resources\Encashments;

use App\Filament\Resources\Encashments\Pages\CreateEncashment;
use App\Filament\Resources\Encashments\Pages\EditEncashment;
use App\Filament\Resources\Encashments\Pages\ListEncashments;
use App\Filament\Resources\Encashments\Schemas\EncashmentForm;
use App\Filament\Resources\Encashments\Tables\EncashmentsTable;
use App\Models\Encashment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EncashmentResource extends Resource
{
    protected static ?string $model = Encashment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::Banknotes;
    protected static \UnitEnum|string|null $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Encashment';

    public static function form(Schema $schema): Schema
    {
        return EncashmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EncashmentsTable::configure($table);
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
            'index' => ListEncashments::route('/'),
            'create' => CreateEncashment::route('/create'),
            'edit' => EditEncashment::route('/{record}/edit'),
        ];
    }
}
