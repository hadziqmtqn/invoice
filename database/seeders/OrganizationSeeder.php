<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organization = new Organization();
        $organization->name = 'Bekenweb';
        $organization->organization_id = '891726328';
        $organization->save();
    }
}
