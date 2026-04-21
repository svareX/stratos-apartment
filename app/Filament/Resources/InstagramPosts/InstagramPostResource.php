<?php

namespace App\Filament\Resources\InstagramPosts;

use App\Filament\Resources\InstagramPosts\Pages\CreateInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\EditInstagramPost;
use App\Filament\Resources\InstagramPosts\Pages\ListInstagramPosts;
use App\Filament\Resources\InstagramPosts\Schemas\InstagramPostForm;
use App\Filament\Resources\InstagramPosts\Tables\InstagramPostsTable;
use App\Models\InstagramPost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class InstagramPostResource extends Resource
{
    protected static ?string $model = InstagramPost::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static ?string $recordTitleAttribute = 'caption';

    public static function getNavigationLabel(): string
    {
        return __('Instagram Posts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('General');
    }

    public static function getModelLabel(): string
    {
        return __('Instagram Post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Instagram Posts');
    }

    public function getTitle(): string|Htmlable
    {
        return __('Manage Instagram Posts');
    }

    public static function form(Schema $schema): Schema
    {
        return InstagramPostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InstagramPostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInstagramPosts::route('/'),
            'create' => CreateInstagramPost::route('/create'),
            'edit' => EditInstagramPost::route('/{record}/edit'),
        ];
    }
}
