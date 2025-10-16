<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactInfo extends Model
{
    protected $table = 'contact_info';

    protected $fillable = [
        'address',
        'address_en',
        'phone',
        'email',
        'facebook',
        'twitter',
        'instagram',
        'whatsapp',
        'tiktok'
    ];
}
