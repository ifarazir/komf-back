<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'title', 'course_id'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function vocabs()
    {
        return $this->belongsToMany(Vocab::class);
    }
}
