<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticResource\Pages;
use App\Models\Statistic;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class StatisticResource extends Resource
{
    protected static ?string $model = Statistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    
    protected static ?string $navigationLabel = 'Statistic';
    protected static ?string $pluralModelLabel = 'Statistic';
    protected static ?string $navigationGroup = 'Tracking';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // Statistik hanya tampil, tidak perlu form input
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event')->sortable()->searchable(),
                TextColumn::make('ip')->label('IP Address'),
                TextColumn::make('user_agent')->limit(50)->label('User Agent'),
                TextColumn::make('utm_source')->label('UTM Source'),
                TextColumn::make('utm_medium')->label('UTM Medium'),
                TextColumn::make('utm_campaign')->label('UTM Campaign'),
                TextColumn::make('created_at')->dateTime()->label('Waktu Akses'),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStatistics::route('/'),
        ];
    }

}
