<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Support\Enums\FontWeight;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    // Group 1: Identity
                    ImageColumn::make('avatar_url')
                        ->circular()
                        ->grow(false),

                    Stack::make([
                        TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->size('md') // Increased size for better visual weight
                            ->searchable()
                            ->sortable(),
                        TextColumn::make('email')
                            ->size('xs') // Fixed: Using string 'xs'
                            ->color('gray')
                            ->searchable(),
                    ])->space(1),

                    // Group 2: Status (Right-aligned inside the card)
                    Stack::make([
                        TextColumn::make('email_verified_at')
                            ->badge()
                            ->state(fn ($record): string => $record->email_verified_at ? 'Verified' : 'Pending')
                            ->color(fn ($state): string => $state === 'Verified' ? 'success' : 'warning')
                            ->icon(fn ($state): string => $state === 'Verified' ? 'heroicon-m-check-badge' : 'heroicon-m-clock'),
                        
                        TextColumn::make('two_factor_confirmed_at')
                            ->badge()
                            // ->formatStateUsing(fn ($record) => $record->two_factor_confirmed_at === null ? '2FA Active' : '2FA Disabled')
                            ->state(fn ($record) => $record->two_factor_confirmed_at === null ? '2FA Disabled' : '2FA Active')
                            ->size('xs')
                            ->color(fn ($state) => $state ? 'info' : 'gray')
                            ->icon(fn ($state) => $state ? 'heroicon-m-shield-check' : 'heroicon-m-shield-exclamation'),
                    ])
                    ->visibleFrom('md')
                    ->alignment('right') // Correct way to align cluster to the right
                    ->space(1),
                ]),
                // Removed ->verticalAlignment() and ->gap() as they cause BadMethodCallException
            ])
            ->contentGrid([
                'md' => 1,
                'lg' => 2,
                '2xl' => 3,
            ])
            ->actions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton()->color('warning'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}