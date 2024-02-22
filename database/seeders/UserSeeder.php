<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('admin');

        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@staff.com',
            'password' => bcrypt('password'),
        ]);

        $staff->assignRole('staff');

        $keeper = User::create([
            'name' => 'Keeper',
            'email' => 'keeper@keeper.com',
            'password' => bcrypt('password'),
        ]);

        $keeper->assignRole('keeper');
    }
}
