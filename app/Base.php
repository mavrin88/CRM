<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use EloquentFilter\Filterable;

class Base extends Model
{
    //use SoftDeletes;

    use Filterable;

    protected $fillable = [
        'child_name',
        'age',
        'mother_name',
        'father_name',
        'other_relative_name',
        'mother_phone',
        'father_phone',
        'other_relative_phone',
        'child_surname',
        'child_middle_name',
        'branch',
        'manager',
        'instructor',
        'programm_id',
        'avatar',
        'source',
    ];

    // protected $with = ['journal'];

    public function contracts(){
    	return $this->hasMany(Contract::class);
    }

    public function base_branch(){
    	return $this->hasOne('App\Branch', 'id', 'branch');
    }

    public function source_list(){
        return $this->hasOne('App\Source', 'id', 'source');
    }

    public function user_block_name(){
        return $this->hasOne('App\User', 'id', 'user_block_id');
    }

    public function base_manager(){
        return $this->hasOne('App\User', 'id', 'manager');
    }

    public function base_instructor(){
        return $this->hasOne('App\User', 'id', 'instructor');
    }

    public function programm(){
        return $this->hasOne('App\Programm', 'id', 'programm_id');
    }

//    public function scopeFilter($builder, $filters)
//    {
//        return $filters->apply($builder);
//    }

    public function journal(){
        return $this->hasMany(Journal::class);
    }

//    public function comments(){
//        return $this->hasMany(Comments::class);
//    }

    public function loger(){
        return $this->hasMany(Loger::class);
    }

    public function documents(){
        return $this->hasMany(Document::class);
    }

    public function statuses(){
        return $this->hasOne(Statuses::class);
    }

// Под вопросом
    public function group(){
        return $this->hasOne('App\Group', 'id', 'group_id');
    }
}
