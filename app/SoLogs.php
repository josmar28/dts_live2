<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoLogs extends Model
{
    protected $connection = 'dtr';
    protected $table = 'so_logs';
    protected $primaryKey = 'id';
}
