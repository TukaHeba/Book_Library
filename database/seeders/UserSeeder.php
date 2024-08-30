<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $adminUser->assignRole('admin');

        $clientUser = User::create([
            'name' => 'tuka',
            'email' => 'tuka@gmail.com',
            'password' => Hash::make('12345678'),
        ]);
        $clientUser->assignRole('client');
    }
}
