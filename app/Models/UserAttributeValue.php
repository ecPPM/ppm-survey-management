<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttributeValue extends Model
{
    protected $fillable = [
        'user_id',
        'user_attribute_id',
        'value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userAttribute()
    {
        return $this->belongsTo(UserAttribute::class, 'user_attribute_id');
    }
}
