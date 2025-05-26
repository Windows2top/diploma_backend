<?php

namespace App\Models;

use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'lection'
    ];

    /**
     * Get the questions for this test.
     */
    public function questions() {
        return $this->hasMany(Question::class);
    }

     /**
     * The users associated with this test (e.g., who took or were assigned the test).
     */
    public function users() {
        return $this->belongsToMany(User::class);
    }
}
