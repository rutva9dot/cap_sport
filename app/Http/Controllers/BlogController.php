<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $blogs = Blog::all();
            return view('blogs.index', compact('blogs'));
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
            return view('blogs.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $about_us = new Blog();
        $about_us->title = $request->title;
        $about_us->content = $this->storeBase64Image($request->description);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('blog_image/', $imageBase64);
            $about_us->image = $upload_image;
        }
        $about_us->save();

        return redirect()->action([BlogController::class, 'index'])->with('success', 'Data saved successfully');
    }

    private function storeBase64Image($description)
    {
        $pattern = '/src="data:image\/(\w+);base64,([^"]+)/';
        $savePath = public_path('blog_img'); // Path to save images

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
                $publicPath = asset('blog_img/' . $fileName);
                $description = str_replace($matches[0][$key], 'src="' . $publicPath, $description);
            }
        }

        return $description;
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blogs = Blog::find($id);
        if($blogs) {
            return view('blogs.edit', compact('blogs'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $blogs = Blog::find($id);
        $blogs->title = $request->title ?? $blogs->title;
        $blogs->content = $this->storeBase64Image($request->description);

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolder('blog_image/', $imageBase64);
            $blogs->image = $upload_image;
        }
        $blogs->save();

        return redirect()->action([BlogController::class, 'index'])->with('success', 'Data saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $blogs = Blog::find($id);
        if ($blogs) {
            $blogs->delete();

            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'Blog Not Found']);
    }
}
