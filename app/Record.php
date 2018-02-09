<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public $timestamps = true;
    protected $table = 'record';
    protected $guarded = ['id'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
