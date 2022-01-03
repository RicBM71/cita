<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class UsersPermissionsController extends Controller
{
    public function show(Request $request, User $user){

        if ($request->wantsJson())
            return [
                'permisos' => Permission::all(),
            ];

    }


	public function update(Request $request, User $user)
    {

        if ($request->wantsJson()){
            $user->syncPermissions($request->input('permisos')); // esto es del paquete laravel permisions

            return [
                'permisos_usr' => $user->getDirectPermissions()->pluck('name')
            ];
        }
    }

}
