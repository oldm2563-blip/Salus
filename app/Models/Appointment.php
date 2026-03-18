<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'appointment_date',
        'status',
        'doctor_id',
        'user_id'
    ];
}
