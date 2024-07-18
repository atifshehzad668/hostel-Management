<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transection extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'transection_type_id',
        'amount',
        'transection_date',
        'description',
        'status',
        'transection_type',
    ];

    /**
     * Get the staff that owns the Transection
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
