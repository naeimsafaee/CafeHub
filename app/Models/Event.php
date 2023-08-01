<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Morilog\Jalali\Jalalian;

class Event extends Model
{
    use HasFactory;

    public function getDateAttribute()
    {
        return fa_number(Jalalian::forge($this->time_at)->format('%d  %B  %Y'));
    }

    public function getHourAttribute()
    {
        if (Jalalian::forge($this->time_at)->getHour() <= 12)
            return fa_number(Jalalian::forge($this->time_at)->getHour()) . ' ظهر';

        else
            return fa_number(Jalalian::forge($this->time_at)->getHour()) . ' عصر';

    }


}
