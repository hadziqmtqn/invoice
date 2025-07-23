<?php

namespace Database\Seeders;

use App\Models\ZohoConfig;
use Illuminate\Database\Seeder;

class ZohoConfigSeeder extends Seeder
{
    public function run(): void
    {
        $zohoConfig = new ZohoConfig();
        $zohoConfig->organization_id = 1;
        $zohoConfig->code = '1000.256ed35626b66a8f78eee66aa4455909.188862d6f806153d12f63fca674a35ef';
        $zohoConfig->client_id = '1000.JM2C8O0HQSY8T5AIA18YWL3E6RJTKY';
        $zohoConfig->client_secret = '5af225bee38ba428c995c4e831d598b0e5124d6c78';
        $zohoConfig->redirect_url = 'http://www.zoho.com/invoice';
        $zohoConfig->refresh_token = '1000.c020723e9e9a7f8f2d74bcde33fe87a1.55a9ac5cb83229764acd8e8a6b58d83c';
        $zohoConfig->save();
    }
}
