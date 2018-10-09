<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    protected $table = 'matches';

    public $timestamps = false;

    public function commands1()
    {
        return $this->belongsTo('App\Commands', 'command1', 'id');
    }

    public function commands2()
    {
        return $this->belongsTo('App\Commands', 'command2', 'id');
    }

    public function info()
    {
        return $this->hasMany('App\Info', 'match', 'id');
    }

}
