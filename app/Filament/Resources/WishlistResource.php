<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WishlistResource\Pages;
use App\Models\Wishlist;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class WishlistResource extends Resource
{
    protected static ?string $model = Wishlist::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product Name')->searchable(),
                TextColumn::make('product.product_id')->label('Product ID')->searchable(),
                TextColumn::make('product.price')->label('Product Price')->searchable(),
                TextColumn::make('user.phone_number')->label('Buyer Phone Number')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWishlists::route('/'),
        ];
    }
}
