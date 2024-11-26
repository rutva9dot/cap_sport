<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Country;
class Coach extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'coaches';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'designation',
        'country_id',
        'about',
        'certification',
        'image',
    ];

    public function getImageAttribute($value)
    {
        $ImageBaseUrl = config('global.website_url') . 'coach_profile/';
        $imageNames = explode(',', $value);
        // $imageUrls = [];

        foreach ($imageNames as $imageName) {
            $imageName = trim($imageName); // Trim any whitespace
            $imagePath = public_path('coach_profile/' . $imageName);

            if ($imageName && file_exists($imagePath)) {
                $imageUrls = $ImageBaseUrl . $imageName;
            }
        }

        return !empty($imageUrls) ?  $imageUrls : null;
    }

    // App\Models\Coach.php
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

}
