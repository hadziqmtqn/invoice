<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\ZohoConfig;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ZohoConfigSeeder extends Seeder
{
    /**
     * @throws FileNotFoundException
     */
    public function run(): void
    {
        $zohoConfigs = json_decode(File::get(database_path('import/zoho-configs.json')), true);

        foreach ($zohoConfigs as $config) {
            $organization = Organization::where('organization_id', $config['organization_id'])
                ->first();

            ZohoConfig::updateOrCreate(
                ['organization_id' => $organization->id],
                [
                    'code' => $config['code'],
                    'client_id' => $config['client_id'],
                    'client_secret' => $config['client_secret'],
                    'redirect_uri' => $config['redirect_uri'],
                    'refresh_token' => $config['refresh_token']
                ]
            );
        }
    }
}
