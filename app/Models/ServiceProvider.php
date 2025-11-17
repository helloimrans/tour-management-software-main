<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class ServiceProvider extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
//    protected $appends = ['logo'];
//
//    public function getLogoUrlAttribute()
//    {
//        return url(Storage::url($this->logo));
//    }
    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id','id');
    }
    public function services()
    {
        return $this->belongsTo(Service::class, 'service_id','id');
    }
    public function radioStation(){
        return $this->belongsTo(RadioStation::class, 'radio_id','id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by','id')->withDefault([
            'name' => 'N/A',
        ]);
    }
    public function updatedBy(){
        return $this->belongsTo(User::class, 'updated_by','id')->withDefault([
            'name' => 'N/A',
        ]);
    }
}
