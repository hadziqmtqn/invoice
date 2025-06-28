<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = new User();
        $user->name = 'Super Admin';
        $user->email = 'superadmin@bkn.my.id';
        $user->password = Hash::make('superadmin');
        $user->save();
    }
}
