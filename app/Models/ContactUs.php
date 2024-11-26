<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'contact_us';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name',
        'email',
        'contact_no',
        'age_level',
        'lesson_program',
        'location',
        'massage',
    ];

    public function ageLevel() {
        return $this->belongsTo(AgeLevel::class, 'age_level');
    }

    public function lessonProgram() {
        return $this->belongsTo(LessonProgram::class, 'lesson_program');
    }

    public function location() {
        return $this->belongsTo(OurLocation::class, 'location');
    }
}
