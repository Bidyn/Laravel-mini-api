<?php

namespace App\Http\Controllers;

use App\Http\Requests\IpCallbackRequest;
use App\Http\Requests\IpRequest;
use App\Services\Geolocator;
use App\Transformers\GeolocationTransformer;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{
    /**
     * @var Geolocator
     */
    private $geolocator;
    /**
     * @var GeolocationTransformer
     */
    private $transformer;

    public function __construct(Geolocator $geolocator, GeolocationTransformer $transformer)
    {
        $this->geolocator = $geolocator;
        $this->transformer = $transformer;
    }

    public function getJson(IpRequest $request)
    {
        return $this->getTransformedLocation($request);
    }

    public function getJsonp(IpCallbackRequest $request)
    {
        $location = $this->getTransformedLocation($request);
        return response()->json($location)
            ->withCallback($request->input('callback'));
    }

    public function getSoap(IpRequest $request)
    {
        $location = $this->getTransformedLocation($request);
        $content = view('soapResponse', compact('location'));
        return response($content, 200)
            ->withHeaders(["Content-type" => "text/xml"]);
    }

    protected function getTransformedLocation(Request $request): array
    {
        $location = $this->getLocation($request);

        $location = $this->transformer->transform($location);

        return $location;
    }

    protected function getLocation(Request $request): \App\GeoLocation
    {
        return $this->geolocator
            ->getLocationForIp($request->input('ip', $request->ip()));
    }
}
