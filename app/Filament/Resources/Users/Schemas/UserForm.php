<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required(),
                TextInput::make('password')
                    ->visibleOn('create')
                    ->password()
                    ->revealable()
                    ->required(),
                TextInput::make('password_confirmation')
                    ->visibleOn('create')
                    ->same('password')
                    ->dehydrated(false)
                    ->password()
                    ->revealable()
                    ->label('Confirm Password')
                    ->required(),
                Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'suspended' => 'Suspended',
                    ])
                    ->default('active')
                    ->required(),
            ]);
    }
}
