<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'authors' => 'array',
        'number_of_pages' => 'integer',
        'released' => 'date',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class)->count();
    }

}
