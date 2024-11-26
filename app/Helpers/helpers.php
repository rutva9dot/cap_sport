<?php

use Illuminate\Http\Testing\File;

if (!function_exists('UploadImageFolder')) {
    function UploadImageFolder($folder_name, $file_name)
    {
        $paths = public_path($folder_name);
        if (!is_dir($paths)) {
            mkdir($paths, 0755, true);
        }
        $filename = time() . '_' . str_replace(' ', '', $file_name->getClientOriginalName());
        $file_name->move($paths, $filename);
        return $filename;
    }
}

if (!function_exists('deleteDataFromFolder')) {
    function deleteDataFromFolder($folder_name, $image_name)
    {
        $delete_image = public_path() . $folder_name . $image_name;
        if (File::exists($delete_image)) {
            File::delete($delete_image);
        }
        return true;
    }
}

if (!function_exists('UploadImageFolderBase64')) {
    function UploadImageFolderBase64($folder_name, $imageBase64)
    {
        $paths = public_path($folder_name);
        if (!is_dir($paths)) {
            mkdir($paths, 0755, true);
        }

        list($type, $imageBase64) = explode(';', $imageBase64);
        list(, $imageBase64)      = explode(',', $imageBase64);
        $image = base64_decode($imageBase64);

        $imageName = time() . '_' . 'images.png';
        $path = $paths . $imageName;
        file_put_contents($path, $image);

        return $imageName;
    }
}

if (!function_exists('getFullImagePath')) {
    function getFullImagePath($relativePath)
    {
        return url($relativePath); // This assumes the relative path is correct
    }
}
