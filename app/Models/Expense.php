<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes as EloquentSoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['expense_head_id', 'amount', 'paying_date', 'description'];

    public function expense_head()
    {
        return $this->belongsTo(ExpenseHead::class);
    }
}