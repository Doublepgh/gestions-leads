<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole      = Role::firstOrCreate(['name' => 'admin']);
        $operadorRole   = Role::firstOrCreate(['name' => 'operador']);

        $manageEverything   = Permission::firstOrCreate(['name' => 'manage everything']);
        $manageLeads = Permission::firstOrCreate(['name' => 'manage leads']);

        $adminRole->givePermissionTo([$manageEverything, $manageLeads]);
        $operadorRole->givePermissionTo([$manageLeads]);

        $user1 = User::find(2);

        if ($user1) {
                $user1->assignRole('admin', 'operador');
            }
        }


}
