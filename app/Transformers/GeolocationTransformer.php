<?php


namespace App\Transformers;


use App\GeoLocation;

class GeolocationTransformer
{
    public function transform(GeoLocation $location)
    {
        return [
            'coordinates' => [
                'latitude' => $location['lat'],
                'longitude' => $location['lon']
            ],
            'country' => $location['country']
        ];
    }
}