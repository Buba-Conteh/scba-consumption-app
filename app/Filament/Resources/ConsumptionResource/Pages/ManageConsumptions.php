<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\ConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageConsumption extends ManageRecords
{
    protected static string $resource = ConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
