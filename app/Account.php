<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id', 'deleted_at'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
