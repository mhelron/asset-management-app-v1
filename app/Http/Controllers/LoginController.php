<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{

    // Redirect to previous page if available, otherwise dashboard (Pag authenticated na si user at inaccess si login page, babalik sa previous page or dashboard)
    public function showLogin(Request $request) {
        if (auth()->check()) {
            $previousUrl = url()->previous();
    
            // If the previous URL is login, redirect to dashboard
            if ($previousUrl === route('login.form')) {
                return redirect()->route('dashboard.index');
            }
    
            // Redirect back to the previous page (like categories, dashboard, etc.)
            return redirect()->to($previousUrl);
        }

    // Check if the previous URL was a protected page
    $intendedUrl = session('url.intended');

    // Protected routes (Para sa error message ng protected routes)
    $protectedRoutes = [
        url('/dashboard'),
        url('/users'),
        url('/categories'),
        url('/inventory'),
        url('/custom-fields'),
        url('/departments'),
        url('/components'),
        url('/accessories'),
    ];

    $currentPath = '/' . request()->path(); // e.g. '/users/create-user'


    if ($intendedUrl) {
        foreach ($protectedRoutes as $prefix) {
            if (Str::startsWith($intendedUrl, $prefix)) {
                session()->flash('error', 'You must be logged in to access this page.');
                break;
            }
        }
    }

         return view('login.login');
    }


    public function processLogin (Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        // Check if email exists in the database
        $user = User::where('email', $email)->first();

        if (!$user) {
            // Email doesn't exist, clear everything
            return back()->withInput([])->with('error', 'User not found. Please try again');
        }

        // Email exists, now check credentials (This code contains remember me functionality)
        $remember = $request->filled('remember');

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            session(['email' => Auth::user()->email]);

            // Clear the stored intended URL so it won't redirect there
            session()->forget('url.intended');

            return redirect()->intended('dashboard');
        }


         // Password incorrect: keep email only
            return back()->withInput(['email' => $email]) ->with('error', 'Incorrect password. Please try again');
    }


    public function logout(Request $request){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login.form'); // back to login page after logout
      }
}
