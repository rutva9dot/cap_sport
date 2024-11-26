<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {

            $coaches = Coach::join('countries', 'coaches.country_id', '=', 'countries.id')
                        ->select('coaches.*', 'countries.name as country_name')
                        ->orderBy('coaches.id', 'desc')
                        ->get();

            return view('coaches.index', compact('coaches'));
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
            $countries = Country::select('id', 'name')->get();

            return view('coaches.create', compact('countries'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coaches = new Coach();
        $coaches->name = $request->name;
        $coaches->designation = $request->designation;
        $coaches->certification = $request->certification;
        $coaches->country_id = $request->country_id;
        $coaches->about = $this->storeBase64Image($request->about);

        $image = $request->file('image');
        if ($request->hasFile('image')) {
            $upload_image       = UploadImageFolder('coach_profile', $image);
            $coaches->image   = $upload_image;
        }

        $coaches->save();

        return redirect()->action([CoachController::class, 'index'])->with('success', 'Data saved successfully');
    }

    private function storeBase64Image($description)
    {
        $pattern = '/src="data:image\/(\w+);base64,([^"]+)/';
        $savePath = public_path('coach_about'); // Path to save images

        // Ensure the directory exists
        if (!File::exists($savePath)) {
            File::makeDirectory($savePath, 0755, true);
        }

        if (preg_match_all($pattern, $description, $matches)) {
            foreach ($matches[2] as $key => $base64Image) {
                $imageData = base64_decode($base64Image);
                $extension = $matches[1][$key];
                $fileName = Str::random(10) . '.' . $extension;
                $filePath = $savePath . '/' . $fileName;

                // Save the image
                File::put($filePath, $imageData);

                // Replace the base64 string with the URL of the stored image
                $publicPath = asset('coach_about/' . $fileName);
                $description = str_replace($matches[0][$key], 'src="' . $publicPath, $description);
            }
        }

        return $description;
    }

    /**
     * Display the specified resource.
     */
    public function show(Coach $coach)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $coaches = Coach::find($id);
        if($coaches) {

            $countries = Country::select('id', 'name')->get();
            return view('coaches.edit', compact('coaches', 'countries'));
        } else {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $coaches = Coach::find($id);
        $coaches->name = $request->name ?? $coaches->name;
        $coaches->designation = $request->designation ?? $coaches->designation;
        $coaches->certification = $request->certification ?? $coaches->certification;
        $coaches->country_id = $request->country_id ?? $coaches->country_id;
        $coaches->about = $this->storeBase64Image($request->about  ?? $coaches->about);

        $image = $request->file('image');
        if ($request->hasFile('image')) {
            $upload_image       = UploadImageFolder('coach_profile', $image);
            $coaches->image   = $upload_image;
        }

        $coaches->save();

        return redirect()->action([CoachController::class, 'index'])->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $coach = Coach::find($id);
        if ($coach) {
            $coach->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'Coach Not Found']);
    }
}
