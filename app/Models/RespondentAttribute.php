<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespondentAttribute extends Model
{
    // Polymorphic relation to get options
    public function options()
    {
        return $this->morphMany(Option::class, 'optionable');
    }
}
