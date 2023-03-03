<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Inventory;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        Inventory::query()->create([
            "product_id" => $this->record->id,
            "product_name" => $this->record->name,
            "product_code" => $this->record->product_id,
            "average_cost_price" => $this->record->offer_price,
            "total_cost" => 0,
            "quantity" => 0,
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
