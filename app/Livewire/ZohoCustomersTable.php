<?php

namespace App\Livewire;

use App\Models\ZohoConfig;
use App\Services\ZohoCustomerService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class ZohoCustomersTable extends Component
{
    public ?int $organizationId = null;

    /**
     * Mendapatkan daftar customer dari Zoho Invoice API.
     */
    public function getCustomers(): array
    {
        $customers = [];
        $organizationId = $this->organizationId;

        // Ambil konfigurasi Zoho
        if ($organizationId) {
            $config = ZohoConfig::where('organization_id', $organizationId)->first();
        } else {
            $config = ZohoConfig::first();
        }

        if ($config) {
            try {
                $zohoCustomerService = new ZohoCustomerService();
                // Pastikan service return array of customers
                $customers = $zohoCustomerService->getCustomers($config);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        return $customers;
    }

    public function render(): View
    {
        return view('livewire.zoho-customers-table', [
            'customers' => $this->getCustomers(),
        ]);
    }
}
