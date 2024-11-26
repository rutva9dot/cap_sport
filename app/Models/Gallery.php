<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'galleries';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'venue_id',
        'images',
    ];

    // public function getImagesAttribute($value)
    // {
    //     $ImageBaseUrl = config('global.website_url') . 'gallery_image/';
    //     $imageNames = explode(',', $value);
    //     $imageUrls = [];

    //     foreach ($imageNames as $imageName) {
    //         $imageName = trim($imageName); // Trim any whitespace
    //         $imagePath = public_path('gallery_image/' . $imageName);

    //         if ($imageName && file_exists($imagePath)) {
    //             $imageUrls[] = $ImageBaseUrl . $imageName;
    //         }
    //     }

    //     return !empty($imageUrls) ?  $imageUrls : null;
    // }
}
