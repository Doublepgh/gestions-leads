<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'ver leads']);
        Permission::create(['name' => 'crear leads']);
        Permission::create(['name' => 'asignar leads']);

        // Crear roles y asignar permisos
        $admin = Role::create(['name' => 'admin']);
        $operador = Role::create(['name' => 'operador']);

        $admin->givePermissionTo(Permission::all());
        $operador->givePermissionTo('ver leads');
    }
}
