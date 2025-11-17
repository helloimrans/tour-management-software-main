<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laratrust\Traits\HasRolesAndPermissions;
use Laratrust\Contracts\LaratrustUser as LaratrustUserContract;

class User extends Authenticatable implements LaratrustUserContract
{
    use HasFactory, Notifiable, SoftDeletes, HasRolesAndPermissions;

    const ADMIN_USER_CODE = '1';
    const NORMAL_USER_CODE = '2';
    const TRAVEL_AGENCY_USER_CODE = '3';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'phone',
        'own_coupon_code',
        'used_coupon_code',
        'profile_pic',
        'tour_id',
        'address',
        'company_name',
    ];

    protected $appends = ['profile_pic_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfilePicUrlAttribute(): string
    {
        return url(Storage::url($this->profile_pic));
    }

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

    public function scopeAdminUser($query){
        return $query->where(['user_type' => self::ADMIN_USER_CODE]);
    }

    public function scopeGeneralUser($query){
        return $query->where(['user_type' => self::NORMAL_USER_CODE]);
    }

    public function scopeTravelAgency($query){
        return $query->where(['user_type' => self::TRAVEL_AGENCY_USER_CODE]);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tour_id', 'id');
    }
}
