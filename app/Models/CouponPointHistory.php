<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponPointHistory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const BY_REGISTRATION = 'by_registration';
    const BY_REFERENCE = 'by_reference';
}
