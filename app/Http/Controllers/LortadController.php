<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class LortadController extends Controller
{
    public function index()
    {
        $user = User::findOrfail(auth()->user()->id);

        if ($user->lortad) {
            return Redirect::route('dashboard');
        } else {
            return Inertia::render('Lortad');
        }

    }

    public function update()
    {

        $user = User::findOrfail(auth()->user()->id);

        $input['username_umod'] = auth()->user()->email;
        $input['lortad']        = Carbon::now();

        $user->update($input);

        //return Redirect::route('dashboard');
        return Redirect::back()->with('success', "Usuario {$user->username} actualizado!");

    }
}
