<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'banners';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'title',
        'image',
    ];

    public function getImageAttribute($value)
    {
        $ImageBaseUrl = config('global.website_url') . 'banner_image/';
        $imageNames = explode(',', $value);
        // $imageUrls = [];

        foreach ($imageNames as $imageName) {
            $imageName = trim($imageName); // Trim any whitespace
            $imagePath = public_path('banner_image/' . $imageName);

            if ($imageName && file_exists($imagePath)) {
                $imageUrls = $ImageBaseUrl . $imageName;
            }
        }

        return !empty($imageUrls) ?  $imageUrls : null;
    }
}
