<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'  =>  'admin',                
                'email'  =>  'admin@gmail.com',                
                'password'  =>  Hash::make('password123'),                
            ],
            [
                'name'  =>  'customer',                
                'email'  =>  'customer@gmail.com',                
                'password'  =>  Hash::make('password123'),                
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $admin = User::where('name', 'admin')->first();
        $customer = User::where('name', 'customer')->first();

        $admin->assignRole('admin');
        $customer->assignRole('customer');
    }
}
