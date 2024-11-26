<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Session::has('a_type')) {
            $Banners = Banner::orderBy('id', 'asc')->get();
            return view('banners.index', compact('Banners'));
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
            return view('banners.create');
        } else {
            return redirect('login');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $banner = new Banner();
        $banner->title = $request->title;

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolderBase64('banner_image/', $imageBase64);
            $banner->image = $upload_image;
        }
        $banner->save();

        return redirect()->action([BannerController::class, 'index'])->with('success', 'Data saved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (Session::has('a_type')) {
            $banner = Banner::find($id);
            if($banner) {
                return view('banners.edit', compact('banner'));
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
    public function update(Request $request, $id)
    {
        $banner = Banner::find($id);
        $banner->title = $request->title ?? $banner->title;

        $imageBase64 = $request->image;
        if ($imageBase64 != null) {
            $upload_image = UploadImageFolderBase64('banner_image/', $imageBase64);
            $banner->image = $upload_image;
        }
        $banner->save();

        return redirect()->action([BannerController::class, 'index'])->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::find($id);
        if ($banner) {
            $banner->delete();
            return response()->json(['status' => true, 'message' => 'Data deleted successfully']);
        }
        return response()->json(['status' => false, 'message' => 'Banner Not Found']);
    }
}
