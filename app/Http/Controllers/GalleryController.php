<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $galleries = Gallery::join('venues', 'galleries.venue_id', '=', 'venues.id')
                        ->select('galleries.*', 'venues.name as venue_name')
                        ->get();

            return view('gallery.index', compact('galleries'));
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
            $venues = Venues::select('id', 'name')->get();

            return view('gallery.create', compact('venues'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $galleries = new Gallery();
        $galleries->venue_id = $request->venue_id;

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // $path = $image->store('images', 'public');
                $path = UploadImageFolder('gallery_image/', $image);
                $imagePaths[] = $path;
            }
        }
        $imagePathsString = implode(',', $imagePaths);

        $galleries->images = $imagePathsString;
        $galleries->save();

        return redirect()->action([GalleryController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        if (Session::has('a_type')) {
            return view('gallery.view', compact('gallery'));
        } else {
            return redirect('login');
        }
    }

    public function deleteImage(Request $request)
    {
        // Validate the request to ensure 'image' is provided
        $request->validate([
            'image' => 'required|string',
        ]);

        $imageName = $request->input('image'); // Get the image name from the request
        $imagePath = public_path('gallery_image/' . $imageName); // Construct the file path

        // Check if the file exists
        if (File::exists($imagePath)) {
            // Delete the file from the filesystem
            File::delete($imagePath);

            // Update the database
            // Assuming you are storing images in a comma-separated string in your 'galleries' table
            $gallery = Gallery::find($request->gallery_id); // Replace with your logic for fetching the gallery
            if ($gallery) {
                $images = explode(',', $gallery->images);
                $updatedImages = array_diff($images, [$imageName]); // Remove the deleted image from the array
                $gallery->images = implode(',', $updatedImages); // Update the images field
                $gallery->save();
            }

            // Respond with success
            return response()->json(['success' => true, 'message' => 'Image deleted successfully.']);
        }

        // If the file doesn't exist, return an error
        return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        dd($gallery);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        //
    }
}
