<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttribute extends Model
{
    protected $fillable = [
        'survey_id',
        'name',
        'display_text',
        'order',
        'is_required',
        'field_type'
    ];

    // Polymorphic relation to get options
    public function options()
    {
        return $this->morphMany(Option::class, 'optionable');
    }
}
