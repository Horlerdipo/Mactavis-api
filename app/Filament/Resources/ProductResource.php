<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('product_id')->label('Product ID')->required()->unique(ignoreRecord: true)->columnSpan(12),

                TextInput::make('name')->label('Product Name')->required()->columnSpan(12),

                Select::make('brand_id')->label('Brand')->relationship('brand', 'name')->required()->columnSpan(6),

                Select::make('category_id')->label('Category')->relationship('category', 'name')->required()->columnSpan(6),

                Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->directory('medias')
                            ->visibility('public')
                            ->imageResizeTargetWidth('900')
                            ->imageResizeTargetHeight('900')
                            ->multiple()
                            ->disableLabel(),

                        FileUpload::make('video_url')->directory('videos')->acceptedFileTypes(['video/mp4', 'video/3gpp']),
                    ])->columnSpan(12)
                    ->collapsible(),

                RichEditor::make('description')->label('Description')->required()->disableToolbarButtons([
                    'attachFiles',
                    'codeBlock',
                ])->columnSpan(12),

                TextInput::make('retail_price')->label('Retail Price')->required()->numeric()->columnSpan(6),

                TextInput::make('offer_price')->label('Offer Price')->required()->numeric()->columnSpan(6),

                TextInput::make('reseller_price')->label('Reseller Price')->required()->numeric()->columnSpan(6),

                TextInput::make('box_price')->label('Original Box Price')->required()->numeric()->columnSpan(6),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->url(fn (Product $record): string => $record->getFirstMediaUrl())
                    ->openUrlInNewTab(),
                TextColumn::make('product_id')->searchable()->copyable()
                    ->copyMessage('Product ID copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('name')->searchable(),
                TextColumn::make('category.name')->label('Category Name')->searchable(),
                TextColumn::make('brand.name')->label('Brand Name')->searchable(),
                TextColumn::make('retail_price')->prefix('Rs.'),
                TextColumn::make('offer_price')->prefix('Rs.'),
                TextColumn::make('reseller_price')->prefix('Rs.'),
                TextColumn::make('box_price')->prefix('Rs.'),
                TextColumn::make('quantity'),
                TextColumn::make('video_url')->url(fn (Product $record) => $record->video_url)
                    ->openUrlInNewTab(),
            ])
            ->filters([
                //
            ])
            ->actions([

                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
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
}
