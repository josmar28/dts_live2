<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tracking_Filter extends Model
{
    protected $table = 'tracking_filter';
    protected $primaryKey = 'id';
    protected $fillable = [
        'doc_type',
        'doc_description',
        'description'
    ];
}
