<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class RadioStation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $appends = ['thumbnail_url', 'logo_url', 'under_maintenence_image_url'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault([
            'name' => '--',
        ]);
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault([
            'name' => '--',
        ]);
    }

    public function relaksTv(): HasOne
    {
        return $this->hasOne(RelaksTv::class);
    }


    public function getThumbnailUrlAttribute(): string
    {
        return url(Storage::url($this->thumbnail_image));
    }
    public function getLogoUrlAttribute(): string
    {
        return url(Storage::url($this->logo));
    }
    public function getUnderMaintenenceImageUrlAttribute(): string
    {
        return url(Storage::url($this->under_maintenence_image));
    }

    public function scopeIsActive($query){
        return $query->where(['is_active' => 1]);
    }
}
