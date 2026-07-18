<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'country',
        'subject',
        'source',
        'instagram_handle',
        'message',
    ];
}
