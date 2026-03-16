<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = [
        'name',
        'severity',
        'description',
        'date_recorded',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'date_recorded' => 'date',
        'notes' => 'json'
    ];
}
