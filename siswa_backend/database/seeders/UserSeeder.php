<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
        $user = User::where('email','admin@example.com')->first();
        if(!$user){
           User::create([
                'name'     => 'Admin',
                'email'    => 'admin@example.com',
                'password' => Hash::make('password123'),
            ]); 
        }
        
    }
}