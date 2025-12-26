<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Пользователи';
    protected static ?string $pluralModelLabel = 'Пользователи';
    protected static ?string $modelLabel = 'Пользователь';

   // 1. Ограничение входа: Менеджер не видит этот раздел
    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['root', 'admin']);
    }

    // 2. Глобальный фильтр: Admin видит в списке ТОЛЬКО менеджеров
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user->hasRole('root')) {
            return $query;
        }

        if ($user->hasRole('admin')) {
            // Показываем только тех, у кого роль 'manager'
            return $query->whereHas('roles', function ($q) {
                $q->where('name', 'manager');
            });
        }

        return $query->whereRaw('1 = 0');
    }

    // 3. Запрет на редактирование: Если Admin попытается зайти к Root/Admin по ссылке
    public static function canEdit($record): bool
    {
        $user = auth()->user();

        if ($user->hasRole('root')) {
            return true;
        }

        // Admin может редактировать только manager
        return $record->hasRole('manager');
    }

    // 4. Запрет на удаление
    public static function canDelete($record): bool
    {
        $user = auth()->user();

        if ($user->hasRole('root')) {
            return $record->id !== $user->id;
        }

        return $record->hasRole('manager');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Данные аккаунта')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Имя')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label('Пароль')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->revealable(),

                        Forms\Components\Select::make('roles')
                            ->label('Роль')
                            ->relationship(
                            'roles', 
                  'name',
           fn (Builder $query) => auth()->user()->hasRole('root') 
                              ? $query 
                             : $query->where('name', 'manager') // Admin видит только manager
                            )
                            ->preload()
                            ->searchable()
                            ->required()
                            // Админ может создавать/назначать только менеджеров
                            ->options(function () {
                                if (auth()->user()->hasRole('root')) {
                                    return \Spatie\Permission\Models\Role::pluck('name', 'id');
                                }
                                return \Spatie\Permission\Models\Role::where('name', 'manager')->pluck('name', 'id');
                            }),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Имя')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Роль')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'root' => 'danger',
                        'admin' => 'warning',
                        'manager' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата создания')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->label('Фильтр по ролям')
                    ->relationship('roles', 'name'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}