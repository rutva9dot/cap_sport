<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AboutUs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'about_us';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'title',
        'content',
        'image'
    ];

    public function getImageAttribute($value)
    {
        $ImageBaseUrl = config('global.website_url') . 'about_image/';
        $imageNames = explode(',', $value);
        // $imageUrls = [];

        foreach ($imageNames as $imageName) {
            $imageName = trim($imageName); // Trim any whitespace
            $imagePath = public_path('about_image/' . $imageName);

            if ($imageName && file_exists($imagePath)) {
                $imageUrls = $ImageBaseUrl . $imageName;
            }
        }

        return !empty($imageUrls) ?  $imageUrls : null;
    }

    public function getFullContentAttribute()
    {
        return $this->parseContentImages($this->content);
    }

    protected function parseContentImages($content)
    {
        $baseUrl = config('global.website_url');

        // Use a regular expression to find image src attributes and replace them with the full URL
        $content = preg_replace_callback('/<img[^>]+src=["\']?([^"\'>]+)["\']?[^>]*>/i', function ($matches) use ($baseUrl) {
            $relativePath = $matches[1];
            if (strpos($relativePath, 'http') === false) {
                $fullPath = rtrim($baseUrl, '/') . '/' . ltrim($relativePath, '/');
                return str_replace($relativePath, $fullPath, $matches[0]);
            }
            return $matches[0];
        }, $content);

        return $content;
    }
}
