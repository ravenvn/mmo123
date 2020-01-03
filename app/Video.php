<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id', 'deleted_at'];

    public function warehouse()
    {
    	return $this->belongsTo(warehouse::class);
    }
}