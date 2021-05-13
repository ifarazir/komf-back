<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $fillable = 
    [
        'title',
        'q1',
        'q2',
        'q3',
        'q4',
        'answer',
        'lesson_id'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
