<?php

namespace App\Http\Controllers;

use App\Models\OurLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OurLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $ourLocation = OurLocation::orderBy('id', 'desc')->get();
            return view('our-location.index', compact('ourLocation'));
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
            return view('our-location.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $location = new OurLocation();
        $location->name = $request->name;
        $location->address = $request->address;
        $location->save();

        return redirect()->action([OurLocationController::class, 'index'])->with('success', 'Data saved successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(OurLocation $ourLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        if (Session::has('a_type')) {
            $ourLocation = OurLocation::find($id);

            return view('our-location.edit', compact('ourLocation'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = OurLocation::find($id);
        $location->name = $request->name ?? $location->name;
        $location->address = $request->address ?? $location->address;
        $location->save();

        return redirect()->action([OurLocationController::class, 'index'])->with('success', 'Data updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ourLocation = OurLocation::find($id);
        if ($ourLocation) {
            $ourLocation->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'ourLocation Not Found']);
    }
}
