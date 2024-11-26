<?php

namespace App\Http\Controllers;

use App\Models\VenueDetails;
use App\Models\Venues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VenueDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $venue_details = VenueDetails::join('venues', 'venue_details.venue_id', '=', 'venues.id')
                        ->select('venue_details.*', 'venues.name as venue_name')
                        ->orderBy('venue_details.id', 'desc')
                        ->get();

            return view('venues-details.index', compact('venue_details'));
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
            $storedVenueIds = VenueDetails::pluck('venue_id')->toArray();

            // Fetch venues excluding those already stored
            $venues = Venues::select('id', 'name')
                ->whereNotIn('id', $storedVenueIds)
                ->get();

            return view('venues-details.create', compact('venues'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $VenueDetails = new VenueDetails();
        $VenueDetails->title = $request->title;
        $VenueDetails->venue_id = $request->venue_id;
        $VenueDetails->content = $this->storeBase64Image($request->content);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('venue_image/', $imageBase64);
            $VenueDetails->image = $upload_image;
        }
        $VenueDetails->save();

        return redirect()->action([VenueDetailsController::class, 'index'])->with('success', 'Data saved successfully');
    }

    private function storeBase64Image($description)
    {
        $pattern = '/src="data:image\/(\w+);base64,([^"]+)/';
        $savePath = public_path('venue_img'); // Path to save images

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
                $publicPath = asset('venue_img/' . $fileName);
                $description = str_replace($matches[0][$key], 'src="' . $publicPath, $description);
            }
        }

        return $description;
    }

    /**
     * Display the specified resource.
     */
    public function show(VenueDetails $venueDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Session::has('a_type')) {
            $venueDetails = VenueDetails::find($id);
            if ($venueDetails) {
                $storedVenueIds = VenueDetails::where('id', '!=', $id)->pluck('venue_id')->toArray();

                $venues = Venues::select('id', 'name')
                    ->whereNotIn('id', $storedVenueIds)
                    ->orWhere('id', $venueDetails->venue_id)
                    ->get();

                return view('venues-details.edit', compact('venueDetails', 'venues'));
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
        $VenueDetails = VenueDetails::find($id);
        $VenueDetails->title = $request->title ?? $VenueDetails->title;
        $VenueDetails->venue_id = $request->venue_id ?? $VenueDetails->venue_id;
        $VenueDetails->content = $this->storeBase64Image($request->content);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('venue_image/', $imageBase64);
            $VenueDetails->image = $upload_image;
        }
        $VenueDetails->save();

        return redirect()->action([VenueDetailsController::class, 'index'])->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $venueDetails = VenueDetails::find($id);
        if($venueDetails) {
            $venueDetails->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'VenueDetails Not Found']);
        }
    }
}
