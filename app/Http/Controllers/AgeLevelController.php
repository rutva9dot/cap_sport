<?php

namespace App\Http\Controllers;

use App\Models\AgeLevel;
use App\Models\LessonProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AgeLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $ageLevel = AgeLevel::orderBy('id', 'desc')->get();
            $LessonProgram = LessonProgram::orderBy('id', 'desc')->get();
            return view('age-level.index', compact('ageLevel', 'LessonProgram'));
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
            return view('age-level.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ageLevel = new AgeLevel();
        $ageLevel->title = $request->title;
        $ageLevel->save();

        return redirect()->action([AgeLevelController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(AgeLevel $ageLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ageLevel = AgeLevel::find($id);
        if (Session::has('a_type')) {
            if($ageLevel) {
                return view('age-level.edit', compact('ageLevel'));
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
        $ageLevel = AgeLevel::find($id);
        if (Session::has('a_type')) {
            $ageLevel->title = $request->title ?? $ageLevel->title;
            $ageLevel->save();

            return redirect()->action([AgeLevelController::class, 'index'])->with('success', 'Data updated successfully');
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ageLevel = AgeLevel::find($id);
        if($ageLevel) {
            $ageLevel->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'AgeLevel Not Found']);
        }
    }
}
