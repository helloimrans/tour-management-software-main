<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointWithdrawRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    const APPROVE = 'approved';
    const REJECT = 'reject';

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id')->withDefault(['name' => 'N/A']);
    }
}
