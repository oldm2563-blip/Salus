<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ai_Respond extends Model
{
    protected $table = 'ai_responds';

    protected $fillable = [
        'response',
        'generated_at',
        'user_id'
    ];
}
