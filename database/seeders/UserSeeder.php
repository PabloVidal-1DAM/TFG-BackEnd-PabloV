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
        // Se crean los usuarios (10 Clientes y 1 Admin).
        User::factory(10)->create()->each(function($user) {
            $user->assignRole('usuario');
        });

        User::create([
            "nombre"=>'PabloAdmin',
            "email"=>"admin@tetrabios.com",
            "password"=>Hash::make('123456')
        ])->assignRole('admin');
    }
}
