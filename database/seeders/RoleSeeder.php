<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Consejero(a)']);
        $role3 = Role::create(['name' => 'Oficialia de Partes']);
        $role4 = Role::create(['name' => 'Director']);
        $role5 = Role::create(['name' => 'Coordinador']);

        Permission::create(['name' => 'Lista de roles', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear rol', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar rol', 'area' => 'Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar rol', 'area' => 'Roles'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de permisos', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Crear permiso', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Editar permiso', 'area' => 'Permisos'])->syncRoles([$role1]);
        Permission::create(['name' => 'Borrar permiso', 'area' => 'Permisos'])->syncRoles([$role1]);

        Permission::create(['name' => 'Lista de dependencias', 'area' => 'Dependencias'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Crear dependencia', 'area' => 'Dependencias'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Editar dependencia', 'area' => 'Dependencias'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Borrar dependencia', 'area' => 'Dependencias'])->syncRoles([$role1]);


        Permission::create(['name' => 'Lista de usuarios', 'area' => 'Usuarios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Crear usuario', 'area' => 'Usuarios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Editar usuario', 'area' => 'Usuarios'])->syncRoles([$role1, $role2]);
        Permission::create(['name' => 'Borrar usuario', 'area' => 'Usuarios']);

        Permission::create(['name' => 'Lista de seguimientos', 'area' => 'Seguimiento'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Crear seguimiento', 'area' => 'Seguimiento'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Editar seguimiento', 'area' => 'Seguimiento'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Borrar seguimiento', 'area' => 'Seguimiento'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);

        Permission::create(['name' => 'Lista de conclusiones', 'area' => 'Conclusiones'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Crear conclusion', 'area' => 'Conclusiones'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Editar conclusion', 'area' => 'Conclusiones'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Borrar conclusion', 'area' => 'Conclusiones'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);

        Permission::create(['name' => 'Lista de entradas', 'area' => 'Entradas'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Ver entrada', 'area' => 'Entradas'])->syncRoles([$role1, $role2, $role3, $role4, $role5]);
        Permission::create(['name' => 'Crear entrada', 'area' => 'Entradas'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'Editar entrada', 'area' => 'Entradas'])->syncRoles([$role1, $role2, $role3]);
        Permission::create(['name' => 'Borrar entrada', 'area' => 'Entradas'])->syncRoles([$role1, $role2, $role3]);
    }
}
