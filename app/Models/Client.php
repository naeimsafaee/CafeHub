<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use TCG\Voyager\Facades\Voyager;


class Client extends Authenticatable{
    use HasFactory , HasApiTokens , Notifiable;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'phone',
        'is_verify',
        'code',
        'avatar'
    ];
    protected $appends =['image' ];

    public function getImageAttribute() {

        if ($this->avatar)
            return Voyager::image($this->avatar);
        else
            return asset('assets/photo/avatar.png');
    }


        public function licence(){
        return $this->belongsTo(Licence::class);
    }

    public function guards(){
        return $this->hasManyThrough(Guard::class, Client_licence::class);
    }

}
