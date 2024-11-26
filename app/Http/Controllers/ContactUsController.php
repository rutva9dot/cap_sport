<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $ContactUs = ContactUs::select('contact_us.*',
                    'age_levels.title as age_level_title',
                    'lesson_programs.title as lesson_program_title',
                    'our_locations.name as location_name'
                )
                ->join('age_levels', 'contact_us.age_level', '=', 'age_levels.id')
                ->join('lesson_programs', 'contact_us.lesson_program', '=', 'lesson_programs.id')
                ->join('our_locations', 'contact_us.location', '=', 'our_locations.id')
                ->orderBy('contact_us.id', 'desc')
                ->get();

            return view('contact-us.index', compact('ContactUs'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactUs $contactUs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactUs $contactUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactUs $contactUs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactUs $contactUs)
    {
        //
    }
}
