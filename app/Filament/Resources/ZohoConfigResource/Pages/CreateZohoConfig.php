<?php

namespace App\Filament\Resources\ZohoConfigResource\Pages;

use App\Filament\Resources\ZohoConfigResource;
use Filament\Resources\Pages\CreateRecord;

class CreateZohoConfig extends CreateRecord
{
    protected static string $resource = ZohoConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
