<?php

namespace App\Filament\Resources\ZohoConfigResource\Pages;

use App\Filament\Resources\ZohoConfigResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZohoConfig extends EditRecord
{
    protected static string $resource = ZohoConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
