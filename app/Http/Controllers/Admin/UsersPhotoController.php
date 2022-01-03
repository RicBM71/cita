<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class UsersPhotoController extends Controller
{
    public function destroy(User $user)
    {

        $this->authorize('update', $user);

        $user->deleteProfilePhoto();

        return Inertia::render('Admin/Users/UserEdit', [
            'user' => $user
        ]);

    }
}
