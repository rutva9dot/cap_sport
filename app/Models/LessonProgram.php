<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonProgram extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lesson_programs';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'title',
    ];
}
