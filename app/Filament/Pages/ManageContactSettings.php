<?php

namespace App\Filament\Pages;

use App\Models\ContactSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

class ManageContactSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Phone;

    protected string $view = 'filament.pages.manage-contact-settings';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('Contact Settings');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('General');
    }

    public static function getModelLabel(): string
    {
        return __('Contact Settings');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Contact Settings');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Manage Contact Settings');
    }

    public function mount(): void
    {
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make(__('Contact Info'))
                        ->schema([
                            TextInput::make('email')
                                ->label(__('Email'))
                                ->email()
                                ->required(),
                            TextInput::make('phone')
                                ->label(__('Phone'))
                                ->required(),
                            TextInput::make('vat')
                                ->label(__('ID (IČO)'))
                                ->required(),
                        ])->columns(3),

                    Section::make(__('Address'))
                        ->schema([
                            Tabs::make('Translations')
                                ->columnSpan('full')
                                ->tabs([
                                    Tab::make(__('English'))
                                        ->icon('heroicon-m-language')
                                        ->schema([
                                            Textarea::make('address_en')
                                                ->label(__('Address (EN)'))
                                                ->rows(4)
                                                ->required(),
                                        ]),
                                    Tab::make(__('Czech'))
                                        ->icon('heroicon-m-language')
                                        ->schema([
                                            Textarea::make('address_cs')
                                                ->label(__('Address (CS)'))
                                                ->rows(4)
                                                ->required(),
                                        ]),
                                    Tab::make(__('German'))
                                        ->icon('heroicon-m-language')
                                        ->schema([
                                            Textarea::make('address_de')
                                                ->label(__('Address (DE)'))
                                                ->rows(4)
                                                ->required(),
                                        ]),
                                ]),
                        ]),

                    Section::make(__('Social Media'))
                        ->schema([
                            Repeater::make('socials')
                                ->hiddenLabel()
                                ->schema([
                                    TextInput::make('platform')
                                        ->label(__('Platform Shortcode (e.g. FB, IG)'))
                                        ->required(),
                                    TextInput::make('name')
                                        ->label(__('Platform Name'))
                                        ->required(),
                                    TextInput::make('url')
                                        ->label(__('URL'))
                                        ->url()
                                        ->required(),
                                ])
                                ->columns(3)
                                ->reorderable()
                                ->cloneable()
                                ->collapsible()
                                ->defaultItems(0),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label(__('Save'))
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $record = $this->getRecord();

        if (! $record) {
            $record = new ContactSettings;
        }

        $record->fill($data);
        $record->save();

        Notification::make()
            ->success()
            ->title(__('Saved'))
            ->send();
    }

    public function getRecord(): ?ContactSettings
    {
        return ContactSettings::current();
    }
}
