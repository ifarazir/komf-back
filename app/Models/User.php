<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Services\Permission\Traits\HasPermissions;
use App\Services\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasPermissions, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function progress()
    {
        return $this->belongsToMany(Lesson_Vocab::class, 'user_progress', 'user_id', 'lesson_vocab_id');
    }

    public function calculateProgress(Lesson $lesson)
    {
        $lvs = 0;
        $lesson_vocab = Lesson_Vocab::where('lesson_id',$lesson->id)->get();

        foreach ($lesson_vocab as $lv) {
            auth()->user()->progress->contains($lv) ? $lvs +=1 : null;
        }

        return round(($lvs/($lesson_vocab->count()))*100);
    }
}