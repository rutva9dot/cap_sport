<?php

namespace App\Http\Controllers;

use App\Models\FAQs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FAQsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $faqs = FAQs::orderBy('id', 'desc')->get();
            return view('FAQs.index', compact('faqs'));
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
            return view('FAQs.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $faqs = new FAQs();
        $faqs->question = $request->question;
        $faqs->answer = $this->storeBase64Image($request->answer);
        $faqs->save();

        return redirect()->action([FAQsController::class, 'index'])->with('success', 'Data saved successfully');
    }

    private function storeBase64Image($description)
    {
        $pattern = '/src="data:image\/(\w+);base64,([^"]+)/';
        $savePath = public_path('faqs_img'); // Path to save images

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
                $publicPath = asset('faqs_img/' . $fileName);
                $description = str_replace($matches[0][$key], 'src="' . $publicPath, $description);
            }
        }

        return $description;
    }

    /**
     * Display the specified resource.
     */
    public function show(FAQs $fAQs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Session::has('a_type')) {
            $fAQs = FAQs::find($id);
            if($fAQs) {
                return view('FAQs.edit', compact('fAQs'));
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
        $faqs = FAQs::find($id);
        $faqs->question = $request->question ?? $faqs->question;
        $faqs->answer = $this->storeBase64Image($request->answer ?? $faqs->answer);
        $faqs->save();

        return redirect()->action([FAQsController::class, 'index'])->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fAQs = FAQs::find($id);
        if ($fAQs) {
            $fAQs->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'fAQs Not Found']);
    }
}
