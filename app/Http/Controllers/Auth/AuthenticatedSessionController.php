<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

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
        $password = $request->input('password');
        $admin = User::where('email', $request->email)->first();

        if ($admin && Hash::check($password, $admin->password)) {
            session()->put('admin_name', $admin->name);
            session()->put('admin_id', $admin->id);
            session()->put('login_id', $admin->id);
            session()->put('admin_email', $admin->email);
            session()->put('a_type', 1);

            // return response()->json(['success' => true, 'redirect_url' => route('banners.index')]);
            return redirect()->route('banners.index');
        } else {
            // return back()->with('error', 'Invalid email or password');
            return redirect()->back()->with('error', 'Invalid email or password');
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
