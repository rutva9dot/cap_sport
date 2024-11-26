<?php

namespace App\Http\Controllers;

use App\Models\LessonProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LessonProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Session::has('a_type')) {
            return view('lesson-program.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lessonProgram = new LessonProgram();
        $lessonProgram->title = $request->title;
        $lessonProgram->save();

        return redirect()->action([AgeLevelController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(LessonProgram $lessonProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lessonProgram = LessonProgram::find($id);
        if (Session::has('a_type')) {
            if($lessonProgram) {
                return view('lesson-program.edit', compact('lessonProgram'));
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
        $lessonProgram = LessonProgram::find($id);
        if (Session::has('a_type')) {
            $lessonProgram->title = $request->title ?? $lessonProgram->title;
            $lessonProgram->save();

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
        $lessonProgram = LessonProgram::find($id);
        if($lessonProgram) {
            $lessonProgram->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'LessonProgram Not Found']);
        }
    }
}
