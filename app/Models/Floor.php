<?php

namespace App\Models;

use App\Models\Room;
use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Floor extends Model
{
    use HasFactory;
    protected $fillable = [
        'room_name', 'floor_id', 'room_type', 'no_of_seats', 'status',
    ];
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}