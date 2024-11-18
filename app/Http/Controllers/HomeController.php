<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        if (Session::has('a_type')) {
            return view('dashboard');
        }
    }

    public function ChangePassword(Request $request)
    {
        if (Session::has('a_type')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:6',
            ]);
            $id = $request->login_id;
            $admin = User::where('id', $id)->first();
            if ($admin) {
                if (!Hash::check($request->current_password, $admin->password)) {
                    return redirect()->back()->with('error', 'The old password does not match.');
                }
                $admin->password = Hash::make($request->new_password);
                $admin->save();
                return redirect()->back()->with('success', 'Password successfully');
            }
            return back()->with('error', 'Something went wrong, please enter proper data!');
        } else {
            return redirect()->route('login');
        }
    }
}
