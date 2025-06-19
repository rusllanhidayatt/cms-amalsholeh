<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\RelationManagers;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\{FileUpload, RichEditor, Grid};
use Filament\Resources\Resource;
use Filament\Resources\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                    ->required()
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
                    ->searchable()
                    ->createOptionForm([
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
                                while (DB::table('tags')->where('slug', $slug)->exists()) {
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

                        ]),

                FileUpload::make('upload')
                    ->hidden()
                    ->visibility('public')
                    ->directory('')
                    ->image()
                    ->afterStateUpdated(function ($state, Set $set, callable $get) {
                        if ($state) {
                            $url = "/storage/{$state}";
                            $current = $get('content');
                            $set('content', $current . "\n\n<img src=\"{$url}\" alt=\"\" />\n\n");
                        }
                    }),

                RichEditor::make('content')
                    ->label('Content')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('cover')
                    ->image()
                    ->visibility('public')
                    ->directory('')
                    ->required(),

                Forms\Components\Repeater::make('metas') // PostMeta inline
                    ->relationship()
                    ->default([])
                    ->schema([
                        TextInput::make('key'),
                        TextInput::make('value'),
                    ])
                    ->label('Meta Information')
                    ->collapsible(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')->circular(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->sortable(),

                /*
                Tables\Columns\ImageColumn::make('first_image_url')
                    ->label('Gambar Konten')
                    ->height(80)
                    ->width(80)
                    ->circular(),
                */
                
                Tables\Columns\TextColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->colors([
                        'danger' => 'draft',
                        'success' => 'published',
                    ]),

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
