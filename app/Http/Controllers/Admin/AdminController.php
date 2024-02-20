<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Illuminate\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected function getUser(): User
    {
        $user = Auth::user();

        if (!$user instanceof User) {
            abort(403, 'You have to be logged in');
        }

        return $user;
    }
}
