<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::truncate();
        Role::truncate();
        User::truncate();

        $rootRole = Role::create(['name' => 'root']);
        //    $adminRole = Role::create(['name' => 'admin']);
        //    $supRole   = Role::create(['name' => 'supervisor']);

        //    Permission::create(['name' => 'users', 'nombre' => 'Acceso a Usuarios']);
        // Permission::create(['name' => 'excel', 'nombre' => 'Excel']);
        // Permission::create(['name' => 'caja', 'nombre' => 'Caja']);
        // Permission::create(['name' => 'accesos', 'nombre' => 'Accesos']);

        $user = User::factory()->withPersonalTeam()->create([
            'name'        => 'Ricardo',
            'email'       => 'rbartolome@sanaval.com',
            'username'    => 'ricardo.bm',
            'paciente_id' => 806,
        ]);

        User::factory(1)->create();

        $user->assignRole($rootRole);
        //$user->assignRole($adminRole);

        //$adminRole->givePermissionTo('excel');
        //$adminRole->givePermissionTo('users');
        //$user->givePermissionTo('users');
        // $user->givePermissionTo('excel');
    }
}
