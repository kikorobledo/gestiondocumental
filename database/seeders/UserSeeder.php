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
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564321654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Liz',
            'email' => 'correo2@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564324654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Consejero(a)');

        User::create([
            'name' => 'Alejandro',
            'email' => 'correo3@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564324854',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Oficialia de Partes');


        User::create([
            'name' => 'Martin',
            'email' => 'correo4@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '568421654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Director');

        User::create([
            'name' => 'Ana',
            'email' => 'correo5@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564341654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Director');

        User::create([
            'name' => 'Carlos',
            'email' => 'correo6@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564394654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Coordinador');

        User::create([
            'name' => 'Francisco',
            'email' => 'correo7@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '567341654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Coordinador');

        User::create([
            'name' => 'Jorge',
            'email' => 'correo8@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564301454',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Coordinador');

        User::create([
            'name' => 'Claudia',
            'email' => 'correo9@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564321644',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Coordinador');

        User::create([
            'name' => 'Maria',
            'email' => 'correo10@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '564441654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Coordinador');

        User::create([
            'name' => 'Juan',
            'email' => 'correo11@correo.com',
            'location_id' => Arr::random([1,2,3,4,5]),
            'email_verified_at' => Carbon::now(),
            'telefono' => '544321654',
            'password' => Hash::make('12345678'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ])->assignRole('Coordinador');
    }
}
