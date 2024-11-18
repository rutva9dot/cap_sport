<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'venue_details';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'title',
        'content',
        'image',
    ];
}
