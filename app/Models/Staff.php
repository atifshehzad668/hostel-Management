<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;


    /**
     * Get all of the comments for the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transections()
    {
        return $this->hasMany(Transection::class);
    }
}
