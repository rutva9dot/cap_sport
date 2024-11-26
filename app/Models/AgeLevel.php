<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgeLevel extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'age_levels';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'title',
    ];
}
