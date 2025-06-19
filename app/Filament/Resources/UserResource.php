<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserResource extends Resource
{
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }
    
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    
    protected static ?string $navigationLabel = 'User';
    protected static ?string $pluralModelLabel = 'User';
    protected static ?string $navigationGroup = 'Content';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->autocomplete(),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->autocomplete('new-password'),

                Forms\Components\Hidden::make('role')
                    ->default('user'),
            ]);
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'settings' => Pages\Settings::route('/{record}/settings'),
        ];
    }
}
