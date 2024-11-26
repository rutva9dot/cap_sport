<?php

namespace App\Http\Controllers;

use App\Models\ProgramDetails;
use App\Models\Programs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProgramDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $ProgramDetails = ProgramDetails::join('programs', 'program_details.program_id', '=', 'programs.id')
                        ->select('program_details.*', 'programs.name as program_name')
                        ->orderBy('program_details.id', 'desc')
                        ->get();

            return view('program-details.index', compact('ProgramDetails'));
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
            $storedProgramIds = ProgramDetails::pluck('program_id')->toArray();

            // Fetch programs excluding those already stored
            $programs = Programs::select('id', 'name')
                ->whereNotIn('id', $storedProgramIds)
                ->get();

            return view('program-details.create', compact('programs'));
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ProgramDetails = new ProgramDetails();
        $ProgramDetails->title = $request->title;
        $ProgramDetails->program_id = $request->program_id;
        $ProgramDetails->content = $this->storeBase64Image($request->content);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('program_image/', $imageBase64);
            $ProgramDetails->image = $upload_image;
        }
        $ProgramDetails->save();

        return redirect()->action([ProgramDetailsController::class, 'index'])->with('success', 'Data saved successfully');
    }

    private function storeBase64Image($description)
    {
        $pattern = '/src="data:image\/(\w+);base64,([^"]+)/';
        $savePath = public_path('program_img'); // Path to save images

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
                $publicPath = asset('program_img/' . $fileName);
                $description = str_replace($matches[0][$key], 'src="' . $publicPath, $description);
            }
        }

        return $description;
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramDetails $programDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Session::has('a_type')) {
            $programDetails = ProgramDetails::find($id);
            if ($programDetails) {
                $storedProgramIds = ProgramDetails::where('id', '!=', $id)->pluck('program_id')->toArray();

                $Programs = Programs::select('id', 'name')
                    ->whereNotIn('id', $storedProgramIds)
                    ->orWhere('id', $programDetails->program_id)
                    ->get();

                return view('program-details.edit', compact('programDetails', 'Programs'));
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
        $programDetails = ProgramDetails::find($id);
        $programDetails->title = $request->title ?? $programDetails->title;
        $programDetails->program_id = $request->program_id ?? $programDetails->program_id;
        $programDetails->content = $this->storeBase64Image($request->content);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('program_image/', $imageBase64);
            $programDetails->image = $upload_image;
        }
        $programDetails->save();

        return redirect()->action([ProgramDetailsController::class, 'index'])->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $programDetails = ProgramDetails::find($id);
        if($programDetails) {
            $programDetails->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'programDetails Not Found']);
        }
    }
}
