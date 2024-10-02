<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['value', 'display_text'];

    // Polymorphic relationship
    public function optionable()
    {
        return $this->morphTo();
    }
}
