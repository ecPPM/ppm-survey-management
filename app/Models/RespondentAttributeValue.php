<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RespondentAttributeValue extends Model
{
    protected $fillable = [
        'respondent_id',
        'respondent_attribute_id',
        'value',
    ];

    public function respondent()
    {
        return $this->belongsTo(Respondent::class);
    }

    public function respondentAttribute()
    {
        return $this->belongsTo(RespondentAttribute::class, 'respondent_attribute_id');
    }
}
