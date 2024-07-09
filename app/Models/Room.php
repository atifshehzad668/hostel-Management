<?php

namespace App\Models;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'room_name', 'room_type', 'no_of_seats', 'status',
    ];
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function fee()
    {
        return $this->hasMany(Fee::class);
    }
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}