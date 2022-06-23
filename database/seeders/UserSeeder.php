<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Enrique',
            'email' => 'correo@correo.com',
            'email_verified_at' => Carbon::now(),
            'telefono' => '56432146543',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Miguel',
            'email' => 'correo1@correo.com',
            'email_verified_at' => Carbon::now(),
            'telefono' => '56432516542',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Alejandro',
            'email' => 'correo2@correo.com',
            'email_verified_at' => Carbon::now(),
            'telefono' => '56432716541',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Paulina',
            'email' => 'correo3@correo.com',
            'email_verified_at' => Carbon::now(),
            'telefono' => '56432816514',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

        User::create([
            'name' => 'Jesus',
            'email' => 'correo4@correo.com',
            'email_verified_at' => Carbon::now(),
            'telefono' => '56432116514',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Titular');

    }
}
