<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OurLocation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'our_locations';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'address',
    ];
}
