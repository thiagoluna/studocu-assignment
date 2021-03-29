<?php

namespace App\Models;

use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name','email','password'
    ];

    public static function boot()
    {
        parent::boot();

        static::observe(new UserObserver);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * One User can answer Many Questions
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'user_question', 'user_id', 'question_id')
            ->withPivot('user_answer', 'status');
    }
}
