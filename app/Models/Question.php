<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'test_id',
        'title',
        'text',
        'type'
    ];

     /**
     * Get the test this question belongs to.
     */
    public function test() {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get the answers for this question.
     */
    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
