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
        $zohoConfig->code = '1000.e8178e9243319037fb5cf51c3afaae5d.57aaaf8ab601e5dd6944ecea9840e023';
        $zohoConfig->client_id = '1000.JM2C8O0HQSY8T5AIA18YWL3E6RJTKY';
        $zohoConfig->client_secret = '5af225bee38ba428c995c4e831d598b0e5124d6c78';
        $zohoConfig->redirect_url = 'http://www.zoho.com/invoice';
        $zohoConfig->refresh_token = '1000.c020723e9e9a7f8f2d74bcde33fe87a1.55a9ac5cb83229764acd8e8a6b58d83c';
        $zohoConfig->save();
    }
}
