<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Allowed roles (centralized for reuse)
    private array $roles = ['admin', 'magasinier', 'commercial', 'livreur'];

    // -------------------------------------------------------
    // INDEX — Display a listing of all users
    // -------------------------------------------------------
    public function index()
    {
        $users = User::latest()->get();
        return view('user.index', compact('users'));
    }

    // -------------------------------------------------------
    // CREATE — Show the form for creating a new user
    // -------------------------------------------------------
    public function create()
    {
        $roles = $this->roles;
        return view('user.create', compact('roles'));
    }

    // -------------------------------------------------------
    // STORE — Save a newly created user in storage
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:' . implode(',', $this->roles),
            'category' => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'category' => $request->category ?? 'user',
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
            $data['image'] = $imagePath;
        }

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    // -------------------------------------------------------
    // SHOW — Display the specified user
    // -------------------------------------------------------
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    // -------------------------------------------------------
    // EDIT — Show the form for editing the specified user
    // -------------------------------------------------------
    public function edit(User $user)
    {
        $roles = $this->roles;
        return view('user.edit', compact('user', 'roles'));
    }

    // -------------------------------------------------------
    // UPDATE — Update user info (name, username, email, image, category, password)
    // -------------------------------------------------------
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role'     => 'required|in:' . implode(',', $this->roles),
            'category' => 'nullable|string|max:255',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
            'category' => $request->category ?? $user->category,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle image upload — delete old image if a new one is uploaded
        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $imagePath = $request->file('image')->store('users', 'public');
            $data['image'] = $imagePath;
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    // -------------------------------------------------------
    // UPDATE ROLE — Update only the role of the specified user
    // -------------------------------------------------------
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:' . implode(',', $this->roles),
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->route('users.index')
            ->with('success', 'Rôle de l\'utilisateur mis à jour avec succès.');
    }

    // -------------------------------------------------------
    // UPDATE IMAGE — Update only the profile image of the user
    // -------------------------------------------------------
    public function updateImage(Request $request, User $user)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Delete old image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $imagePath = $request->file('image')->store('users', 'public');
        $user->update(['image' => $imagePath]);

        return redirect()->route('users.show', $user->id)
            ->with('success', 'Photo de profil mise à jour avec succès.');
    }

    // -------------------------------------------------------
    // DESTROY — Remove the specified user from storage
    // -------------------------------------------------------
    public function destroy(User $user)
    {
        // Delete profile image from storage if it exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}