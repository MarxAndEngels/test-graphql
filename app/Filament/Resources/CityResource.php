<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Города';
    protected static ?string $modelLabel = 'Город';
    protected static ?string $pluralModelLabel = 'Города';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Название города')
                        ->required()
                        ->live(onBlur: true) // Отправляет запрос при потере фокуса
                        ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                            $operation === 'create' ? $set('slug', Str::slug($state)) : null
                        ), // Автоматически заполняет slug при создании

                    Forms\Components\TextInput::make('slug')
                        ->label('URL Slug')
                        ->required()
                        ->unique(City::class, 'slug', ignoreRecord: true),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
           
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
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