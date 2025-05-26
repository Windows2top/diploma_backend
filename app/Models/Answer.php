<?php

namespace App\Models;

use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'question_id',
        'title',
        'is_correct'
    ];

    /**
     * Get the question this answer belongs to.
     */
    public function question() {
        return $this->belongsTo(Question::class);
    }

    /**
     * The users who selected this answer.
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
