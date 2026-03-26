<?php

namespace App\Filament\Pages;

use App\Models\HomepageSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageHomepageHero extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Photo;

    protected string $view = 'filament.pages.manage-homepage-hero';

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('Homepage Hero Images');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('General');
    }

    public static function getModelLabel(): string
    {
        return __('Homepage Hero Images');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Homepage Hero Images');
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
                    Repeater::make('hero_slides')
                        ->hiddenLabel()
                        ->schema([
                            FileUpload::make('image')
                                ->label(__('Background Image'))
                                ->image()
                                ->directory('hero-images')
                                ->required()
                                ->columnSpan('full'),

                            Toggle::make('is_new')
                                ->label(__('Show "New" badge'))
                                ->default(false)
                                ->columnSpan('full'),

                            Tabs::make('Translations')
                                ->columnSpan('full')
                                ->tabs([
                                    Tab::make(__('English'))
                                        ->icon('heroicon-m-language')
                                        ->schema([
                                            TextInput::make('title_en')
                                                ->label(__('Main Title (EN)'))
                                                ->required(),
                                            TextInput::make('highlighted_title_en')
                                                ->label(__('Highlighted Title Part (EN)')),
                                            Textarea::make('description_en')
                                                ->label(__('Description (EN)'))
                                                ->rows(3)
                                                ->required(),
                                        ]),
                                    Tab::make(__('Czech'))
                                        ->icon('heroicon-m-language')
                                        ->schema([
                                            TextInput::make('title_cs')
                                                ->label(__('Main Title (CS)')),
                                            TextInput::make('highlighted_title_cs')
                                                ->label(__('Highlighted Title Part (CS)')),
                                            Textarea::make('description_cs')
                                                ->label(__('Description (CS)'))
                                                ->rows(3),
                                        ]),
                                    Tab::make(__('German'))
                                        ->icon('heroicon-m-language')
                                        ->schema([
                                            TextInput::make('title_de')
                                                ->label(__('Main Title (DE)')),
                                            TextInput::make('highlighted_title_de')
                                                ->label(__('Highlighted Title Part (DE)')),
                                            Textarea::make('description_de')
                                                ->label(__('Description (DE)'))
                                                ->rows(3),
                                        ]),
                                ]),
                        ])
                        ->columns(2)
                        ->reorderable()
                        ->cloneable()
                        ->collapsible()
                        ->maxItems(10),
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
            $record = new HomepageSettings;
        }

        $record->fill($data);
        $record->save();

        Notification::make()
            ->success()
            ->title(__('Saved'))
            ->send();
    }

    public function getRecord(): ?HomepageSettings
    {
        return HomepageSettings::first();
    }
}
