<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AboutUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $about_us = AboutUs::all();
            return view('about-us.index', compact('about_us'));
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
            return view('about-us.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $about_us = new AboutUs();
        $about_us->title = $request->title;
        $about_us->content = $this->storeBase64Image($request->description);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('about_image/', $imageBase64);
            $about_us->image = $upload_image;
        }
        $about_us->save();

        return redirect()->action([AboutUsController::class, 'index'])->with('success', 'Data saved successfully');
    }

    private function storeBase64Image($description)
    {
        $pattern = '/src="data:image\/(\w+);base64,([^"]+)/';
        $savePath = public_path('about_img'); // Path to save images

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
                $publicPath = asset('about_img/' . $fileName);
                $description = str_replace($matches[0][$key], 'src="' . $publicPath, $description);
            }
        }

        return $description;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $aboutUs = AboutUs::find($id);
        if($aboutUs) {
            return view('about-us.edit', compact('aboutUs'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $about_us = AboutUs::find($id);
        $about_us->title = $request->title ?? $about_us->title;
        $about_us->content = $this->storeBase64Image($request->description);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('about_image/', $imageBase64);
            $about_us->image = $upload_image;
        }
        $about_us->save();

        return redirect()->action([AboutUsController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $aboutUs = AboutUs::find($id);
        if ($aboutUs) {
            $aboutUs->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'AboutUs Not Found']);
    }
}
