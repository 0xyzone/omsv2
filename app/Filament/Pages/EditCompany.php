<?php

namespace App\Filament\Pages;

use App\Models\Company;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Schemas\Schema; 
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Grid;
use Filament\Support\Exceptions\Halt;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
// Unified v4 Layout Components
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use BackedEnum;

class EditCompany extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.edit-company';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::BuildingOffice2;
    public ?array $data = [];

    public function mount(): void
    {
        $company = Company::firstOrNew();
        $this->form->fill($company->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(12) // Using a 12-column grid instead of Split
                    ->schema([
                        
                        // Left Column: Main Company Details (8 Columns)
                        Group::make()
                            ->schema([
                                Section::make('Company Profile')
                                    ->description('General information and contact details.')
                                    ->icon('heroicon-m-building-office-2')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Company Name')
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('email')
                                            ->label('Official Email')
                                            ->email()
                                            ->prefixIcon('heroicon-m-envelope'),
                                        TextInput::make('phone')
                                            ->label('Contact Phone')
                                            ->prefixIcon('heroicon-m-phone'),
                                        TextInput::make('website')
                                            ->label('Website URL')
                                            ->prefixIcon('heroicon-m-globe-alt'),
                                        DatePicker::make('established_date')
                                            ->label('Establishment Date')
                                            ->native(false)
                                            ->prefixIcon('heroicon-m-calendar'),
                                        TextInput::make('address')
                                            ->label('Physical Address')
                                            ->columnSpanFull()
                                            ->prefixIcon('heroicon-m-map-pin'),
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                    ]),

                                Section::make('Legal & Tax Identifiers')
                                    ->description('Government registration numbers.')
                                    ->icon('heroicon-m-scale')
                                    ->columns(3)
                                    ->schema([
                                        TextInput::make('pan_number')->label('PAN'),
                                        TextInput::make('vat_number')->label('VAT'),
                                        TextInput::make('ssf_number')->label('SSF'),
                                        TextInput::make('registration_number')
                                            ->label('Registration Number')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 8]),

                        // Right Column: Branding & Documents (4 Columns)
                        Group::make()
                            ->schema([
                                Section::make('Branding')
                                    ->schema([
                                        FileUpload::make('logo_path')
                                            ->label('Company Logo')
                                            ->image()
                                            ->directory('company-documents')
                                            ->imageEditor()
                                            ->avatar() // Makes it a clean circle
                                            ->alignCenter(),
                                    ]),

                                Section::make('Legal Documents')
                                    ->description('Certificates & Proofs')
                                    ->collapsible()
                                    ->schema([
                                        FileUpload::make('pan_document_path')
                                            ->label('PAN Proof')
                                            ->directory('company-documents'),
                                        FileUpload::make('vat_document_path')
                                            ->label('VAT Proof')
                                            ->directory('company-documents'),
                                        FileUpload::make('registration_document_path')
                                            ->label('Registration Proof')
                                            ->directory('company-documents'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 4]),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->color('primary')
                ->icon('heroicon-m-check-badge')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            Company::updateOrCreate(
                ['id' => Company::first()?->id],
                $data
            );

            Notification::make()
                ->success()
                ->title('Company profile updated')
                ->send();

        } catch (Halt $exception) {
            return;
        }
    }
}