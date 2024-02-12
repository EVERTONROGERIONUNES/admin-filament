<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\Label;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-desktop-computer';

    //TROCA O NOME 
    protected static ?string $navigationLabel = 'Produtos';

    //BUSCA GLOBAL
    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Form $form): Form
    {
        return $form //->columns(3)
            ->schema([
                //Forms\Components\Card::make('Dados 1')->schema([
                // ou Forms\Components\Card::make([
                // ou  Forms\Components\Tabs::make('Tabs')->tabs([
                //Forms\Components\Tabs\Tab::make('Tab1')->schema([
                Forms\Components\Wizard::make()->schema([
                    Forms\Components\Wizard\Step::make('Tab1')->schema([
                        TextInput::make('name')->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $state = Str::slug($state);
                                $set('slug', $state);
                            })
                            ->label('Nome Produto'),
                        Textarea::make('description')->label('Descrição'),
                        TextInput::make('price')->required()->label('Preço'),
                        ]),

                        Forms\Components\Wizard\Step::make('Tab2')->schema([
                        TextInput::make('amount')->required()->label('Quantidade'),
                        TextInput::make('slug')->disabled(),
                        FileUpload::make('photo')
                            ->image()
                            ->directory('products'),

                        Select::make('categories')->relationship('categories', 'name')->multiple()
                    ])
                ])

            ]);
        //])

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')->circular()->height(60),
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('price')->money('BRL')->searchable(),
                TextColumn::make('amount'),
                TextColumn::make('created_at')->date('d/m/y - H:i:s'),
            ])
            ->filters([
                Filter::make('amount')
                    ->toggle()
                    ->label('Estoque inferior a 10')
                    ->query(fn (Builder $query) => $query->where('amount', '<', 10)),

                Filter::make('price')
                    ->toggle()
                    ->label('Menor que R$ 100,00')
                    ->query(fn (Builder $query) => $query->where('price', '<', 10000)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CategoriesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }
}
