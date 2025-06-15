<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    
    protected static ?string $navigationLabel = 'Article';
    protected static ?string $pluralModelLabel = 'Article';
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
                        while (DB::table('articles')->where('slug', $slug)->exists()) {
                            $slug = $baseSlug . '-' . $counter++;
                        }

                        $set('slug', $slug);
                    }),

                Forms\Components\TextInput::make('slug')
                    ->readonly()
                    ->extraAttributes([
                        'class' => 'bg-gray-100 text-gray-700'
                    ])
                    ->placeholder('Slug akan otomatis dibuat dari judul yang sesuai'),
                
                Forms\Components\Select::make('category_id')
                    ->relationship('categories', 'title')
                    ->required(),

                Forms\Components\Select::make('tags')
                    ->label('Tags')
                    ->relationship('tags', 'title') // relasi Artikel ↔ Tag
                    ->multiple()
                    ->preload()
                    ->searchable(),

                Forms\Components\Textarea::make('content')
                    ->rows(10)
                    ->required(),

                Forms\Components\FileUpload::make('cover')
                    ->image()
                    ->directory('posts')
                    ->visibility('public')
                    ->required(),
                    
                Forms\Components\Repeater::make('metas') // PostMeta inline
                    ->relationship()
                    ->schema([
                        TextInput::make('key')->required(),
                        TextInput::make('value'),
                    ])
                    ->label('Meta Information')
                    ->collapsible(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),
                Tables\Columns\TextColumn::make('categories.title')->sortable(),
                Tables\Columns\TextColumn::make('tag.title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('content')
                                            ->sortable()
                                            ->formatStateUsing(fn($state) => ucfirst($state))
                                            ->limit(16),
                Tables\Columns\TextColumn::make('created_at')->date(),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
