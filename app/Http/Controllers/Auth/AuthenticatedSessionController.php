<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        // $request->authenticate();

        // $request->session()->regenerate();

        $admin = User::where('email', $request->email)->first();
        if ($admin) {
            session()->put('admin_name', $admin->name);
            session()->put('admin_id', $admin->id);
            session()->put('login_id', $admin->id);
            session()->put('admin_email', $admin->email);
            session()->put('a_type', 1);

            return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
        } else {
            return redirect()->back()->with('error_msg', 'Invalid email or password');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
