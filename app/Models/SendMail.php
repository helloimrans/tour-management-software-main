<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SendMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_email',
        'to_email',
        'subject',
        'body',
        'send_by',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'send_by', 'id');
    }


}
