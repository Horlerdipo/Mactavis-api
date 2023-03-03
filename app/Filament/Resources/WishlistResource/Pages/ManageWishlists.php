<?php

namespace App\Filament\Resources\WishlistResource\Pages;

use App\Filament\Resources\WishlistResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageWishlists extends ManageRecords
{
    protected static string $resource = WishlistResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
