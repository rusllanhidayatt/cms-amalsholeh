<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingsResource\Pages;
use App\Filament\Resources\SettingsResource\RelationManagers;
use App\Models\Settings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class SettingsResource extends Resource
{
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->where('id', $user->id);
    }

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static bool $isSingleton = true;    
    
    public static function shouldRegisterNavigation(): bool
    {
        return false; // Supaya tidak tampil di sidebar
    }

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
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSettings::route('/{record}/edit'),
        ];
    }
}
