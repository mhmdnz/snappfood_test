<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'priority'];
    const ONCALL = 'on_call';
    const WAITED = 'waited';

    public function updateState($state)
    {
        $this->state = $state;
        return $this->update();
    }

    public static function getFirstWaited()
    {
        return self::where('state', 'waited')->orderBy('priority')->first();
    }
}
