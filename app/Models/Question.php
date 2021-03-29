<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question', 'answer', 'done'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * One Question con be answered by Many Users
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_question','question_id', 'user_id');
    }
}
