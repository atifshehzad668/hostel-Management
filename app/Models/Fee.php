<?php

namespace App\Models;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['registration_id', 'fee_date', 'amount', 'status'];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
}