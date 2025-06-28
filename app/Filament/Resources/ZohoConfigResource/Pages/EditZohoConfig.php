<?php

namespace App\Filament\Resources\ZohoConfigResource\Pages;

use App\Filament\Resources\ZohoConfigResource;
use App\Services\ZohoTokenService;
use Exception;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
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

    protected function afterSave(): void
    {
        try {
            ZohoTokenService::requestAndStoreToken($this->record);
            Notification::make()
                ->title('Success')
                ->body('Token berhasil di-refresh dan disimpan.')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title('Gagal ambil token')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
