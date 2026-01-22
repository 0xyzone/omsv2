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
                Stack::make([
                    Split::make([
                        // Cluster 1: Identity & Role
                        ImageColumn::make('avatar_url')
                            ->circular()
                            ->defaultImageUrl(asset('/images/default_avatar.png'))
                            ->grow(false)
                            ->size(64),

                        Stack::make([
                            TextColumn::make('name')
                                ->weight(FontWeight::Bold)
                                ->size('md')
                                ->searchable()
                                ->sortable()
                                ->iconColor(fn($record): string => $record->hasRole('super_admin') ? 'primary' : ($record->hasRole('taker') ? 'info' : ($record->hasRole('maker') ? 'warning' : 'gray'))),
                            
                            TextColumn::make('email')
                                ->size('xs')
                                ->color('gray')
                                ->searchable(),

                            // Integrated Roles as small badges under the email
                            TextColumn::make('roles.name')
                                ->size('xs')
                                ->limitList(2)
                                ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', $state))
                                ->icon(function ($record) {
                                    return $record->hasRole('super_admin') ? 'heroicon-m-shield-check' : ($record->hasRole('taker') ? 'heroicon-m-clipboard-document-list' : ($record->hasRole('maker') ? 'heroicon-m-bolt' : null));
                                })
                                ->iconColor(function ($record) {
                                    return $record->hasRole('super_admin') ? 'primary' : ($record->hasRole('taker') ? 'info' : ($record->hasRole('maker') ? 'warning' : 'gray'));
                                })
                                ->extraAttributes(['class' => 'px-2 py-1 bg-gray-200/50 dark:bg-gray-600/50 rounded-md w-max capitalize']),
                        ])->space(1),

                        // Cluster 2: Status Badges (Right Aligned)
                        Stack::make([
                            TextColumn::make('email_verified_at')
                                ->badge()
                                ->state(fn($record): string => $record->email_verified_at ? 'Verified' : 'Pending')
                                ->color(fn($state): string => $state === 'Verified' ? 'success' : 'warning')
                                ->icon(fn($state): string => $state === 'Verified' ? 'heroicon-m-check-badge' : 'heroicon-m-clock'),

                            TextColumn::make('two_factor_confirmed_at')
                                ->badge()
                                ->state(fn($record) => $record->two_factor_confirmed_at ? '2FA Active' : '2FA Disabled')
                                ->color(fn($state) => $state === '2FA Active' ? 'info' : 'gray')
                                ->icon(fn($state) => $state === '2FA Active' ? 'heroicon-m-shield-check' : 'heroicon-m-shield-exclamation'),
                        ])
                        ->visibleFrom('md')
                        ->alignment('right')
                        ->grow(false)
                        ->space(1),
                    ]),

                    // Footer Area: Contact Info & Joined Date
                    // This creates a nice horizontal bar at the bottom of the card
                    Split::make([
                        Split::make([
                            TextColumn::make('custom_fields.primary_contact_number')
                                ->icon('heroicon-m-phone')
                                ->size('xs')
                                ->color('gray')
                                ->placeholder('No contact'),
                            TextColumn::make('custom_fields.secondary_contact_number')
                                ->icon('heroicon-m-phone')
                                ->size('xs')
                                ->color('gray'),
                        ]),
                        
                        TextColumn::make('created_at')
                            ->dateTime('M d, Y')
                            ->size('xs')
                            ->color('gray')
                            ->icon('heroicon-m-calendar')
                            ->alignEnd(),
                    ])
                    ->extraAttributes([
                        'class' => 'pt-3 mt-1 border-t border-gray-800 dark:border-gray-500'
                    ]),
                ])
                ->space(3),
            ])
            ->contentGrid([
                'md' => 1,
                'lg' => 2,
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