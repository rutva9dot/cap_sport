<?php

namespace App\Http\Controllers;

use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class VenuesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $venues = Venues::orderBy('id', 'desc')->get();
            return view('venues.index', compact('venues'));
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
            return view('venues.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $venues = new Venues();
        $venues->name = $request->name;
        $venues->slug = Str::slug($request->name);
        $venues->address = $request->address;
        $venues->save();

        return redirect()->action([VenuesController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venues $venues)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $venues = Venues::find($id);
        if (Session::has('a_type')) {
            if($venues) {
                return view('venues.edit', compact('venues'));
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
        $venues = Venues::find($id);
        if (Session::has('a_type')) {
            $venues->name = $request->name ?? $venues->name;
            $venues->address = $request->address ?? $venues->address;
            $venues->slug = Str::slug($request->name ?? $venues->name);
            $venues->save();

            return redirect()->action([VenuesController::class, 'index'])->with('success', 'Data updated successfully');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $venues = Venues::find($id);
        if($venues) {
            $venues->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Venues Not Found']);
        }
    }
}
