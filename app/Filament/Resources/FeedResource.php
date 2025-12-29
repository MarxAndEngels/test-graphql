<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FeedResource\Pages;
use App\Models\Feed;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeedResource extends Resource
{
    protected static ?string $model = Feed::class;

    protected static ?string $navigationIcon = 'heroicon-o-rss';
    protected static ?string $navigationLabel = 'Рекламные фиды';
    protected static ?string $pluralModelLabel = 'Рекламные фиды';
    protected static ?string $modelLabel = 'Фид';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название фида')
                            ->placeholder('Напр: Общий фид для VK')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Тип фида')
                            ->options([
                                'Yandex XML' => 'Yandex XML',
                                'Yandex YML' => 'Yandex YML',
                                'VK XML' => 'VK XML',
                                'Google XML' => 'Google XML',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('url')
                            ->label('URL фида')
                            ->placeholder('https://domain.com/feed.xml')
                            ->url()
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                // Секция для привязки сайтов
                Forms\Components\Section::make('Связанные сайты')
                    ->description('Выберите сайты, для которых предназначен этот фид')
                    ->schema([
                        Forms\Components\Select::make('sites') // Метод sites() в модели Feed
                            ->label('Сайты')
                            ->relationship('sites', 'url') // Показываем URL сайта для выбора
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->hint('Один фид может быть привязан к нескольким сайтам'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Yandex XML', 'Yandex YML' => 'warning',
                        'VK XML' => 'primary',
                        'Google XML' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('url')
                    ->label('Ссылка')
                    ->limit(40)
                    ->copyable()
                    ->color('gray'),

                // // Колонка со счетчиком сайтов
                // Tables\Columns\TextColumn::make('sites_count')
                //     ->label('На сайтах')
                //     ->counts('sites')
                //     ->badge()
                //     ->color('success')
                //     ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип фида')
                    ->options([
                        'Yandex XML' => 'Yandex XML',
                        'Yandex YML' => 'Yandex YML',
                        'VK XML' => 'VK XML',
                        'Google XML' => 'Google XML',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeeds::route('/'),
            'create' => Pages\CreateFeed::route('/create'),
            'edit' => Pages\EditFeed::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
{
    // Разрешаем только root и admin
    return auth()->user()->hasAnyRole(['root', 'admin']);
}

public static function canEdit($record): bool
{
    // Менеджер не может редактировать
    return auth()->user()->hasAnyRole(['root', 'admin']);
}

public static function canDelete($record): bool
{
    // Удаление только для root
    return auth()->user()->hasRole('root');
}
}