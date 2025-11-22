<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class OwnerController extends Controller
{
    public function index()
    {
        $owners = User::role('owner')->with('creator')->latest()->paginate(15);
        return view('superadmin.owners.index', compact('owners'));
    }

    public function create()
    {
        return view('superadmin.owners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $owner = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'created_by' => auth()->id(),
        ]);

        $owner->assignRole('owner');

        return redirect()->route('superadmin.owners.index')->with('success', 'Owner created successfully.');
    }

    public function edit(User $owner)
    {
        return view('superadmin.owners.edit', compact('owner'));
    }

    public function update(Request $request, User $owner)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10,15}$/', 'unique:users,phone,'.$owner->id],
            'password' => ['nullable', Rules\Password::defaults()],
        ]);

        $owner->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $owner->password,
        ]);

        return redirect()->route('superadmin.owners.index')->with('success', 'Owner updated successfully.');
    }

    public function destroy(User $owner)
    {
        $owner->delete();
        return redirect()->route('superadmin.owners.index')->with('success', 'Owner deleted successfully.');
    }

    public function toggleDueSystem(User $owner)
    {
        $owner->update([
            'due_system_enabled' => !$owner->due_system_enabled
        ]);

        $status = $owner->due_system_enabled ? 'চালু' : 'বন্ধ';
        return redirect()->route('superadmin.owners.index')->with('success', "বকেয়া সিস্টেম {$status} করা হয়েছে");
    }
}
