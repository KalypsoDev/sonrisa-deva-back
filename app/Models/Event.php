<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded =  [];

    public function setDateAttribute($value)
    {
        if ($value) {
            $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        }
    }

}
