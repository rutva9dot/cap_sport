<?php

namespace App\Http\Controllers;

use App\Models\CoachPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class CoachPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $coach_plan = CoachPlan::orderBy('id', 'desc')->get();
            return view('plan.index', compact('coach_plan'));
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
            return view('plan.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coach_plan = new CoachPlan();
        $coach_plan->title = $request->title;
        $coach_plan->amount = $request->amount;
        $coach_plan->description = $request->description;
        $coach_plan->save();

        return redirect()->action([CoachPlanController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(CoachPlan $coachPlan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Session::has('a_type')) {
            $coachPlan = CoachPlan::find($id);

            if($coachPlan) {
                return view('plan.edit', compact('coachPlan'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect('login');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $coach_plan = CoachPlan::find($id);
        $coach_plan->title = $request->title ?? $coach_plan->title;
        $coach_plan->amount = $request->amount ?? $coach_plan->amount;
        $coach_plan->description = $request->description ?? $coach_plan->description;
        $coach_plan->save();

        return redirect()->action([CoachPlanController::class, 'index'])->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coach_plan = CoachPlan::find($id);
        if ($coach_plan) {
            $coach_plan->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'AboutUs Not Found']);
    }
}
