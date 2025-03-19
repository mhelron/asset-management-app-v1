<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'user_role' => 'required',
            'first_name' => 'required|regex:/^[a-zA-Z\s\-]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/',
                'confirmed',
            ],
        ]);

        // Create user in SQL database
        $user = User::create([
            'user_role' => $validatedData['user_role'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        /* Log the activity
        Log::info('Activity Log', [
            'user' => auth()->user()->email ?? 'Guest',
            'action' => 'Added a new user: ' . $validatedData['email'] . '.'
        ]);
        */

        return redirect()->route('users.index')->with('success', 'User Added Successfully');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', ['editdata' => $user, 'key' => $id]);
    }

    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'user_role' => 'required',
            'first_name' => 'required|regex:/^[a-zA-Z\s\-]+$/',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8|regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]+$/|confirmed',
        ]);

        $user->update([ 
            'user_role' => $validatedData['user_role'],
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => $request->filled('password') ? Hash::make($validatedData['password']) : $user->password,
        ]);

        Log::info('Activity Log', [
            'user' => Session::get('user_email'),
            'action' => 'Updated user: ' . $validatedData['email']
        ]);

        return redirect('users')->with('success', 'User Updated Successfully');
    }

    public function archive($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return back()->with('error', 'User not found');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User archived successfully.');
    }
}
