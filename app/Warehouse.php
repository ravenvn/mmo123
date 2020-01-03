<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    public const VIDEO_1S = 1;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id', 'deleted_at'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}