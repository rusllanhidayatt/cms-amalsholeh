<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Page;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Category';
    protected static ?string $pluralModelLabel = 'Category';
    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }

                        $baseSlug = Str::slug($state);
                        $slug = $baseSlug;
                        $counter = 1;

                        // Check uniqueness and increment until slug is unique
                        while (DB::table('categories')->where('slug', $slug)->exists()) {
                            $slug = $baseSlug . '-' . $counter++;
                        }

                        $set('slug', $slug);
                    }),

                Forms\Components\TextInput::make('slug')
                    ->readonly()
                    ->required()
                    ->extraAttributes([
                        'class' => 'bg-gray-100 text-gray-700'
                    ])
                    ->placeholder('Slug akan otomatis dibuat dari judul yang sesuai'),
                
                Forms\Components\Textarea::make('description')
                    ->rows(10),

                Forms\Components\FileUpload::make('icon')
                    ->image()
                    ->visibility('public')
                    ->directory('')
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $name = 'AMSOL-' . time() . '.' . $file->getClientOriginalExtension();
                        return $name;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\TextColumn::make('description')
                                            ->sortable()
                                            ->formatStateUsing(fn($state) => ucfirst($state))
                                            ->limit(16),
                Tables\Columns\ImageColumn::make('icon')->circular(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
