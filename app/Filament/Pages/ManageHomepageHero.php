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
                            
                            TextInput::make('title')
                                ->label(__('Main Title'))
                                ->required(),
                            
                            TextInput::make('highlighted_title')
                                ->label(__('Highlighted Title Part')),
                            
                            Textarea::make('description')
                                ->label(__('Description'))
                                ->rows(3)
                                ->required()
                                ->columnSpan('full'),
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
            $record = new HomepageSettings();
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