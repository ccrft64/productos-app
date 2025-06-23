<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'create categories']);
        Permission::firstOrCreate(['name' => 'view categories']);
        Permission::firstOrCreate(['name' => 'edit categories']);
        Permission::firstOrCreate(['name' => 'delete categories']);

        Permission::firstOrCreate(['name' => 'create products']);
        Permission::firstOrCreate(['name' => 'view products']);
        Permission::firstOrCreate(['name' => 'edit products']);
        Permission::firstOrCreate(['name' => 'delete products']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        $adminRole->givePermissionTo(Permission::all());

        $userRole->givePermissionTo(['view categories', 'view products']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser->assignRole('admin');

        $regularUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
            ]
        );
        $regularUser->assignRole('user');

        $this->command->info('¡Roles y permisos creados exitosamente!');
        $this->command->info('Usuario Admin: admin@example.com / password');
        $this->command->info('Usuario Regular: user@example.com / password');
    }
}