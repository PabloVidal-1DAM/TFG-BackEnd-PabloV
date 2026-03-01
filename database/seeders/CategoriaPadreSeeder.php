<?php

namespace Database\Seeders;

use App\Models\CategoriaPadre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaPadreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CategoriaPadre::factory(3)->create();
    }
}
