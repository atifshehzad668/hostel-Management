<?php

namespace App\Models;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}