<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $fillable = [
    	'name',
    	'branch_id',
    ];

    public function schedule_hall() {
        return $this->hasMany(Schedule_hall::class);
    }

}
