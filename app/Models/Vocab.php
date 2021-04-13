<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vocab extends Model
{
    use HasFactory;

    protected $fillable = [ 
        'word', 
        'syn',
        'def',
        'ex1',
        'ex2'
    ];

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }
}