<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteResource\Pages;
use App\Models\Site;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SiteResource extends Resource
{
    protected static ?string $model = Site::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationLabel = 'Сайты';
    protected static ?string $pluralModelLabel = 'Сайты';
    protected static ?string $modelLabel = 'Сайт';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        // Основная колонка (слева)
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Параметры сайта')
                                    ->schema([
                                        Forms\Components\TextInput::make('url')
                                            ->label('URL сайта')
                                            ->required()
                                            ->url()
                                            ->placeholder('https://example.com')
                                            ->unique(ignoreRecord: true),

                                        Forms\Components\Select::make('dealer_id')
                                            ->label('Дилер')
                                            ->relationship('dealer', 'title')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                    ]),

                                Forms\Components\Section::make('Связанные фиды')
                                    ->schema([
                                        Forms\Components\Select::make('feeds')
                                            ->label('Фиды для этого сайта')
                                            ->relationship('feeds', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                    ]),

                                Forms\Components\Section::make('Иерархия (Зеркала)')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_main')
                                            ->label('Это главный сайт')
                                            ->helperText('Отметьте, если сайт является основным')
                                            ->default(false)
                                            ->live(),

                                        Forms\Components\Select::make('parent_id')
                                            ->label('Главный сайт (родитель)')
                                            ->relationship(
                                                'parent', 
                                                'url',
                                                fn (Builder $query) => $query->where('is_main', true)
                                            )
                                            ->searchable()
                                            ->placeholder('Выберите основной сайт')
                                            ->hidden(fn (Forms\Get $get) => $get('is_main'))
                                            ->required(fn (Forms\Get $get) => !$get('is_main')),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // Боковая колонка (справа)
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Статус и Визуал')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Активен')
                                            ->default(true)
                                            ->onColor('success')
                                            ->offColor('danger'),

                                        Forms\Components\FileUpload::make('favicon_image')
                                            ->label('Favicon')
                                            ->image()
                                            ->directory('sites/favicons')
                                            ->imageEditor()
                                            ->avatar()
                                            ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/jpeg', 'image/svg+xml']) // Разрешенные типы
                                            ->maxSize(50024)
                                            ->validationMessages([
                                                'max' => 'Файл слишком большой',
                                            ]),
                                    ]),

                                Forms\Components\Section::make('Инфо')
                                    ->schema([
                                        Forms\Components\Placeholder::make('created_at')
                                            ->label('Создан')
                                            ->content(fn (?Site $record): string => $record?->created_at?->diffForHumans() ?? '-'),
                                        
                                        Forms\Components\Placeholder::make('updated_at')
                                            ->label('Изменен')
                                            ->content(fn (?Site $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                                    ])
                                    ->hidden(fn (?Site $record) => $record === null),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('favicon_image')
                    ->label('')
                    ->circular(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color(fn (Site $record): string => $record->is_main ? 'warning' : 'primary')
                    ->weight(fn (Site $record): string => $record->is_main ? 'bold' : 'normal'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Активен')
                    ->disabled(fn () => !auth()->user()->hasAnyRole(['admin', 'root'])),


                Tables\Columns\TextColumn::make('is_main')
                    ->label('Тип')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Главный' : 'Зеркало')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'warning' : 'gray'),

                Tables\Columns\TextColumn::make('parent.url')
                    ->label('Оригинал')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('dealer.title')
                    ->label('Дилер')
                    ->sortable(),

                Tables\Columns\TextColumn::make('feeds.name')
                    ->label('Фиды')
                    ->badge()
                    ->color('info')
                    ->listWithLineBreaks()
                    ->limitList(2)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Активные'),
                Tables\Filters\TernaryFilter::make('is_main')->label('Главные'),
                Tables\Filters\SelectFilter::make('dealer')
                    ->relationship('dealer', 'title'),
            ])
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
            'index' => Pages\ListSites::route('/'),
            'create' => Pages\CreateSite::route('/create'),
            'edit' => Pages\EditSite::route('/{record}/edit'),
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