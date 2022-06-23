<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Office;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Office::create([
            'name' => 'Dirección General',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Dirección del Registro Público de la Propiedad',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Dirección de Catastro',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Subdirección de planeación estratégica',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Subdirección Jurídica',
            'user_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Subdirección de Tecnologías de la Información',
            'user_id' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);


        Office::create([
            'name' => 'Delegación Administrativa',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Recepción Catastral y Registral',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        Office::create([
            'name' => 'Departamento de Gestión Catastral',
            'user_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Anotaciones y Trámites Administrativos',
            'user_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Operación y Desarrollo de Sistemas',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Operación Presupuestal y Recursos Financieros',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Recursos Humanos, Materiales y Servicios Generales',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de lo Contencioso',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Valuación',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Registro de Inscripciones',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Certificaciones',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Registro de Cartografía',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Base de Datos',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Departamento de Archivo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 1 Zamora',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 2 La Piedad',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 3 Apatzingan',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 4 Uruapan',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 5 Huetamo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 6 Lázaro Cárdenas',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Office::create([
            'name' => 'Coordinación Regional 7 Ciudad Hidalgo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

    }
}
