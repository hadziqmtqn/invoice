<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name' => 'Bekenweb',
                'organization_id' => '891726328',
            ],
            [
                'name' => 'Noname Inc',
                'organization_id' => '894707213',
            ]
                 ] as $item) {
            $organization = new Organization();
            $organization->name = $item['name'];
            $organization->organization_id = $item['organization_id'];
            $organization->save();
        }

    }
}
