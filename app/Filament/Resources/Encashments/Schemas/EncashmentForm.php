<?php

namespace App\Filament\Resources\Encashments\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
// Unified v4 Layout Components
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\Layout\Split;

class EncashmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                    Grid::make(1)
                        ->schema([
                            Section::make('Transaction Details')
                                ->description('Record the user and the specific amount to be encashed.')
                                ->icon('heroicon-m-banknotes')
                                ->columns(2)
                                ->schema([
                                    Select::make('user_id')
                                        ->relationship('user', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->columnSpanFull(),

                                    TextInput::make('amount')
                                        ->label('Encashment Amount')
                                        ->required()
                                        ->numeric()
                                        ->prefix('Rs.') // Changed to local currency based on previous prompts
                                        ->placeholder('0.00'),

                                    DatePicker::make('encashment_date')
                                        ->label('Request Date')
                                        ->default(now())
                                        ->required(),
                                ]),

                            Section::make('Additional Information')
                                ->schema([
                                    Textarea::make('notes')
                                        ->placeholder('Add internal notes or bank reference details...')
                                        ->rows(3),
                                ])
                                ->collapsible(),
                        ])
                        ->grow(true),

                    // Sidebar: Status & Processing
                    Group::make([
                        Section::make('Processing Status')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'approved' => 'Approved',
                                        'completed' => 'Completed',
                                        'rejected' => 'Rejected',
                                    ])
                                    ->required()
                                    ->default('pending')
                                    ->native(false) // Consistent modern UI
                                    ->selectablePlaceholder(false),
                            ]),
                    ])
                    ->grow(false),
            ]);
    }
}