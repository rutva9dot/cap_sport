<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoachPlan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'coach_plans';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'title',
        'amount',
        'description',
    ];
}
