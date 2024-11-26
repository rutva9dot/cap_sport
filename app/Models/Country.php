<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'countries';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'code',
        'flag',
    ];

    public function getFlagAttribute($value)
    {
        $ImageBaseUrl = config('global.website_url') . 'flags/';
        $imageNames = explode(',', $value);
        // $imageUrls = [];
        foreach ($imageNames as $imageName) {
            $imageName = trim($imageName); // Trim any whitespace
            $imagePath = public_path('flags/' . $imageName);
            if ($imageName && file_exists($imagePath)) {
                $imageUrls = $ImageBaseUrl . $imageName;
            }
        }

        return !empty($imageUrls) ?  $imageUrls : null;
    }
}
