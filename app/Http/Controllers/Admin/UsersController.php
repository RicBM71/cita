<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;

class UsersController extends Controller
{
    public function index()
    {

        $this->authorize('view', new User);

        $users = User::permitidos()->paginate(10)->withQueryString(); // L18 - Laracast, conservar search

        //$users = User::all();

        return Inertia::render('Admin/Users/UsersIndex', [
            'paginator' => $users
        ]);
    }

    public function create()
    {

        $this->authorize('update', new User);

        return Inertia::render('Admin/Users/UserCreate');
    }

    public function store(UserStoreRequest $request, User $user)
    {

        $this->authorize('update', $user);

        $input = $request->validated();

        $input['username_umod'] = $request->user()->username;
        $input['password'] = Hash::make('password');

        $user = User::create($input);

        return Redirect::route('users.edit', $user->id);
    }

    public function show(User $user)
    {
        return Redirect::route('users.edit', $user->id);
    }

    public function edit(User $user)
    {

        $this->authorize('update', $user);

        if ($user->id == 1 && !isRoot()) {
            return abort(403, 'No dispones de los permisos necesarios');
        }

        return Inertia::render('Admin/Users/UserEdit', [
            'usuario' => $user,
        ]);
    }

    public function update(UserUpdateRequest $request, User $user)
    {

        $this->authorize('update', $user);

        $input = $request->validated();

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        $input['username_umod'] = auth()->user()->username;

        $user->update($input);

        // return Inertia::render('Admin/Users/UserEdit', [
        //     'usuario' => $user,
        // ]);

        return Redirect::back()->with('success', "Usuario {$user->username} actualizado!");

        //return Redirect::route('users.edit', $user->id);

        // if (config('app.env')=='local')
        //     return Inertia::location('/'); //CORS

    }


    public function destroy(User $user)
    {

        $this->authorize('delete', $user);

        $user->delete();

        //return Redirect::route('users.index');

        return response(['message' => "Usuario ($user->username) Borrado", 'status' => 200]);
    }
}
