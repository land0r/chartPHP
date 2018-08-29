<?php
namespace Database\Seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Backpack\Base\app\Models\BackpackUser;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminPermissions = [
            'display-users',
            'create-users',
            'edit-users',
            'delete-users',
            'display-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'create-permissions',
            'edit-permissions',
            'delete-permissions',
            'display-permissions',
            'display-charts',
            'create-charts',
            'edit-charts',
            'delete-charts'
        ];

        $userPermission = [
            'display-charts',
            'create-charts',
            'edit-charts',
            'delete-charts',
        ];

        $allPermissions = array_unique(array_merge($adminPermissions, $userPermission));

        // create permissions
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // create administrator role
        $administratorRole = Role::create(['guard_name' => 'web', 'name' => 'administrator']);
        $userRole = Role::create(['guard_name' => 'web', 'name' => 'user']);

        // give permissions
        $administratorRole->givePermissionTo($adminPermissions);
        $userRole->givePermissionTo($userPermission);

        // create super admin account
        // assign role to new user
        // save new user to DB
        BackpackUser::create([
            'name' => config('app.administrator_name'),
            'email' => config('app.administrator_email'),
            'password' => bcrypt(config('app.administrator_password')),
        ])->assignRole('administrator');
    }
}
