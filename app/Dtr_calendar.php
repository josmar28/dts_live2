<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dtr_calendar extends Model
{
    protected $connection = 'dtr';
    protected $table = 'calendar';
    protected $primaryKey = 'id';
}
