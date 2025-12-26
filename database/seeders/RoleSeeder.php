<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очистка кэша разрешений spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Создание ролей 
        $rootRole    = Role::create(['name' => 'root']);
        $adminRole   = Role::create(['name' => 'admin']);
        $managerRole = Role::create(['name' => 'manager']);

        // 2. Создание базовых разрешений
        $permissions = [
            'view_admin_panel', // только просмотр
            'manage_users',
            'manage_dealers',
            'manage_sites',
            'manage_feeds',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Назначение прав ролям
        $rootRole->givePermissionTo(Permission::all());
        
        $adminRole->givePermissionTo([
            'view_admin_panel',
            'manage_users', // Должен уметь создавать менеджеров
            'manage_dealers',
            'manage_sites',
            'manage_feeds',
        ]);

        $managerRole->givePermissionTo([
            'view_admin_panel', // Только просмотр
        ]);

        // 4. Создание эталонных пользователей
        $this->createUser('Root', 'root@admin.com', $rootRole);
        $this->createUser('Admin', 'admin@admin.com', $adminRole);
        $this->createUser('Manager', 'manager@admin.com', $managerRole);
    }

    private function createUser(string $name, string $email, Role $role): void
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole($role);
    }
}