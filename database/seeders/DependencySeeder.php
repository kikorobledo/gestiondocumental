<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Dependency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DependencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dependency::create([
            'name' => 'Secreatria de Gobierno',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependency::create([
            'name' => 'Secreatria de Bienestar',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependency::create([
            'name' => 'Secreatria de Finanzas',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependency::create([
            'name' => 'Secreatria de Migración',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependency::create([
            'name' => 'Secreatria de Hacienda',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependency::create([
            'name' => 'Secreatria de la Mujer',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Dependency::create([
            'name' => 'Secreatria del trabajo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
