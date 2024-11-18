<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coach extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'coaches';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'designation',
        'country',
        'about',
        'certification',
        'image',
    ];
}
