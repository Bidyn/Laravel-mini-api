<?php


namespace App\Services;


use App\GeoLocation;

class Geolocator
{
    public function getLocationForIp(string $ip): GeoLocation
    {
        $location = geoip($ip)->toArray();

        return new GeoLocation($location);
    }
}