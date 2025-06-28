<?php

namespace App\Filament\Resources\ZohoConfigResource\Pages;

use App\Filament\Resources\ZohoConfigResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListZohoConfigs extends ListRecords
{
    protected static string $resource = ZohoConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
