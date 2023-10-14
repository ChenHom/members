<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list(Request $request)
    {
        return inertia('User/List', [
            'users' => User::when(
                $request->search,
                fn ($query, $search) => $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
            )
                ->paginate()
        ]);
    }

    public function update(User $user, Request $request)
    {
        $pareUpdate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => 'required|string|max:255',
            'telephone' => 'required|string|min:9|max:15',
        ]);

        $user->update($pareUpdate);

        return back()->with('success', 'User updated.');
    }
}
