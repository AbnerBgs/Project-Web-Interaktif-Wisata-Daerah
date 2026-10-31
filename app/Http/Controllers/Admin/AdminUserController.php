<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'role'      => 'required|in:admin,user',
            'is_active' => 'boolean',
        ]);
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User diperbarui.');
    }

    public function show(User $user)
    {
        $user->load('reviews.destination');
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User dihapus.');
    }
}
