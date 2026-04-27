<?php
namespace App\Filament\Resources;
use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use FilamentTiptapEditor\TiptapEditor;

class PostResource extends Resource {
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $label = 'Kabar Terbaru';

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Konten Utama')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\Section::make()->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Artikel')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(Post::class, 'slug', ignoreRecord: true),
                                Forms\Components\Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'Kegiatan' => 'Kegiatan',
                                        'Informasi' => 'Informasi',
                                        'Prestasi' => 'Prestasi',
                                        'Berita' => 'Berita',
                                    ])
                                    ->required(),
                                Forms\Components\FileUpload::make('thumbnail')
                                    ->image()
                                    ->imageResizeMode('cover')
                                    ->imageResizeTargetWidth('1200')
                                    ->imageEditor()
                                    ->directory('posts')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Ringkasan (Excerpt)')
                                    ->rows(3)
                                    ->helperText('Ringkasan singkat artikel untuk halaman depan dan SEO.')
                                    ->columnSpanFull(),
                                TiptapEditor::make('content')
                                    ->label('Isi Konten')
                                    ->required()
                                    ->profile('default')
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Toggle::make('is_published')
                                        ->label('Publikasikan')
                                        ->default(true),
                                    Forms\Components\DateTimePicker::make('published_at')
                                        ->label('Tanggal Publikasi')
                                        ->default(now()),
                                ]),
                            ])->columns(2)
                        ]),
                    Forms\Components\Tabs\Tab::make('SEO & Metadata')
                        ->icon('heroicon-o-presentation-chart-line')
                        ->schema([
                            Forms\Components\Section::make('Optimasi Mesin Pencari (SEO)')
                                ->description('Atur metadata agar artikel lebih mudah ditemukan di Google.')
                                ->schema([
                                    Forms\Components\TextInput::make('seo_title')
                                        ->label('SEO Title')
                                        ->placeholder('Kosongkan untuk menggunakan judul artikel')
                                        ->maxLength(60)
                                        ->helperText('Judul yang muncul di hasil pencarian. Maksimal 60 karakter.'),
                                    Forms\Components\Textarea::make('seo_description')
                                        ->label('SEO Description')
                                        ->rows(3)
                                        ->maxLength(160)
                                        ->helperText('Ringkasan konten yang muncul di hasil pencarian. Maksimal 160 karakter.'),
                                    Forms\Components\TextInput::make('seo_keywords')
                                        ->label('SEO Keywords')
                                        ->placeholder('contoh: desa, berita, pembangunan')
                                        ->helperText('Pisahkan dengan koma.'),
                                ])
                        ]),
                ])->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table {
        return $table->columns([
            Tables\Columns\ImageColumn::make('thumbnail'),
            Tables\Columns\TextColumn::make('title')->searchable()->wrap(),
            Tables\Columns\TextColumn::make('category')->badge(),
            Tables\Columns\TextColumn::make('views')
                ->label('Dilihat')
                ->numeric()
                ->sortable()
                ->badge()
                ->color('info')
                ->icon('heroicon-o-eye'),
            Tables\Columns\IconColumn::make('is_published')->boolean()->label('Status'),
            Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('category')
                ->options([
                    'Kegiatan' => 'Kegiatan',
                    'Informasi' => 'Informasi',
                    'Prestasi' => 'Prestasi',
                ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
