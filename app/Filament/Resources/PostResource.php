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
            Forms\Components\Section::make('Informasi Utama')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(Post::class, 'slug', ignoreRecord: true),
                Forms\Components\Select::make('category')
                    ->options([
                        'Kegiatan' => 'Kegiatan',
                        'Informasi' => 'Informasi',
                        'Prestasi' => 'Prestasi',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->directory('posts'),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_published')
                    ->default(true),
                Forms\Components\DateTimePicker::make('published_at')
                    ->default(now()),
            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table {
        return $table->columns([
            Tables\Columns\ImageColumn::make('thumbnail'),
            Tables\Columns\TextColumn::make('title')->searchable()->wrap(),
            Tables\Columns\TextColumn::make('category')->badge(),
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
