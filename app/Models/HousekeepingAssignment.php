<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousekeepingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_number',
        'time_slot',
        'status',
    ];
}
