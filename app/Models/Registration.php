<?php

namespace App\Models;

use App\Models\Fee;
use App\Models\Floor;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Registration extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 100, 'h' => 100])
            ->performOnCollections('images')
            ->nonQueued();
    }
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function feeses()
    {
        return $this->hasMany(Fee::class);
    }
}