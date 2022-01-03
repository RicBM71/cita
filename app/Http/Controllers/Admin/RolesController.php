<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index(){

        return Inertia::render('Admin/Roles/RolesIndex',[
            'items' => Role::with('permissions')->get()
        ]);
    }

    public function edit(Role $role){

        $this->authorize('update', $role);

        dd($role);

        return Inertia::render('Admin/Users/UserEdit', [
            'usuario' => $role,
        ]);
    }

}
