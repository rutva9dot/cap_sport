<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FAQs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'faqs';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'question',
        'answer',
    ];
}
