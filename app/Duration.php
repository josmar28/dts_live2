<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duration extends Model
{
    protected $table = 'duration';
    protected $fillable = [
        'section',
        'doc_type',
        'duration'
    ];
}
