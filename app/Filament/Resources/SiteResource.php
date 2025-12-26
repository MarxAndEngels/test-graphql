<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Сайты';
    protected static ?string $pluralModelLabel = 'Сайты';

public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Параметры сайта')
                    ->schema([
                        // Загрузка фавикона
                        Forms\Components\FileUpload::make('favicon_image')
                            ->label('Favicon (иконка)')
                            ->image()
                            ->directory('sites/favicons')
                            ->imageEditor(),

                        // Выбор дилера
                        Forms\Components\Select::make('dealer_id')
                            ->label('Дилер')
                            ->relationship('dealer', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        // Выбор владельца
                        Forms\Components\Select::make('user_id')
                            ->label('Владелец')
                            ->relationship('user', 'name')
                            ->default(auth()->id())
                            ->searchable()
                            ->required(),
                    ]),

                Forms\Components\Section::make('Системная информация')
    ->schema([
        Forms\Components\Placeholder::make('created_at')
            ->label('Дата создания')
            ->content(fn (?Site $record): string => $record?->created_at?->diffForHumans() ?? '-'),

        Forms\Components\Placeholder::make('updated_at')
            ->label('Последнее изменение')
            ->content(fn (?Site $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
    ])
    ->columns(2)
    ->hidden(fn (?Site $record) => $record === null),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('favicon_image')
                    ->label('Иконка')
                    ->circular(),

                Tables\Columns\TextColumn::make('dealer.title')
                    ->label('Дилер')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Владелец')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i') // Формат даты
                    ->placeholder('Не указано') // Что показать, если в базе NULL
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dealer')
                    ->relationship('dealer', 'title')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
        ];
    }
}