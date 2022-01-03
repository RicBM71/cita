<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class UsersRolesController extends Controller
{

    public function show(Request $request, User $user){

        $roles = Role::when(!isRoot(), function ($query) {
            $query->where('name', '<>', 'root');
        })->get()->pluck('name');

        if ($request->wantsJson())
            return [
                'roles' => $roles,
                'roles_usr' => $user->getRoleNames(),
                'permisos_usr' => $user->getDirectPermissions()->pluck('name'),
                'permisos_heredados'=> $user->getPermissionsViaRoles()->unique('name')->sortBy('nombre')->values()->pluck('name')
            ];

        return Redirect::route('dashboard');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        if ($request->wantsJson()){

            $user->syncRoles($request->input('roles')); // esto es del paquete laravel permisions

            return [
                'permisos_heredados'=> $user->getPermissionsViaRoles()->unique('name')->sortBy('nombre')->values()->pluck('name'),
                'permisos_usr' => $user->getDirectPermissions()->pluck('name')
            ];
        }
    }

}
