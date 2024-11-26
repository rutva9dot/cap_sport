<?php

namespace App\Http\Controllers;

use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $Programs = Programs::orderBy('id', 'desc')->get();
            return view('programs.index', compact('Programs'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Session::has('a_type')) {
            return view('programs.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Programs = new Programs();
        $Programs->name = $request->name;
        $Programs->slug = Str::slug($request->name);
        $Programs->save();

        return redirect()->action([ProgramsController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Programs $programs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $Programs = Programs::find($id);
        if (Session::has('a_type')) {
            if($Programs) {
                return view('programs.edit', compact('Programs'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $programs = Programs::find($id);
        if (Session::has('a_type')) {
            $programs->name = $request->name;
            $programs->slug = Str::slug($request->name);
            $programs->save();

            return redirect()->action([ProgramsController::class, 'index'])->with('success', 'Data updated successfully');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $programs = Programs::find($id);
        if($programs) {
            $programs->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Programs Not Found']);
        }
    }
}
