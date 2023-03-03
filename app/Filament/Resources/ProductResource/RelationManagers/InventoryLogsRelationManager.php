<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Enums\InventoryAction;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Vendor;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryLogs';

    protected static ?string $recordTitleAttribute = 'product_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('docket_number')
                    ->columnSpan(12),

                TextInput::make('courier_partner')
                    ->columnSpan(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vendor_name')->searchable(),
                TextColumn::make('vendor_code')->searchable(),
                TextColumn::make('cost_price'),
                TextColumn::make('quantity'),
                BadgeColumn::make('inventory_action')
                    ->colors([
                        'primary',
                        'success' => InventoryAction::ADDITION->value,
                        'danger' => InventoryAction::SUBTRACTION->value,
                    ]),
                TextColumn::make('docket_number')->default("None"),
                TextColumn::make('courier_partner')->default("None"),
                TextColumn::make('updated_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data, RelationManager $livewire): array {
//                    $data['product_id'] = $livewire->ownerRecord->id;
//                    $data['product_code'] = $livewire->ownerRecord->product_id;
//                    $data['product_name'] = $livewire->ownerRecord->name;
//                    return $data;
//                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label("Add Docket Number"),
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
