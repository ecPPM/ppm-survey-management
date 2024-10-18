<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respondent extends Model
{
    protected $fillable = [
        'name',
        'resp_code',
        'user_id',
        'password',
        'survey_id',
        'status'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function enumerator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attributeValues()
    {
        return $this->hasMany(RespondentAttributeValue::class);
    }
}
