<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller{

    public function waze(){
        return _response("https://www.waze.com/live-map/directions?navigate=yes&to=ll." . setting('site.location'));
    }

    public function google_map(){
        return _response("https://www.google.com/maps/dir/?api=1&destination=" . setting('site.location') . "&travelmode=driving" );
    }

    public function prices(){
        return _response([
           "reserv_cafe" => setting('site.reserv_cafe')
        ]);
    }

    public function download_link(){
        return _response([
           "google_play" => setting('site.g_play'),
           "cafe_bazar" => setting('site.bazzar')
        ]);
    }

}
