<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson_Vocab extends Model
{
    use HasFactory;
    
    protected $table = 'lesson_vocab';

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function vocab()
    {
        return $this->belongsTo(Vocab::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
