<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Hospital;
use Illuminate\Http\Request;
use DB;
class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hospitals = Hospital::where('name', '<>', '')->where('lat', '<>', '')->where('lng', '<>', '')->get();
        return view('index', compact('hospitals'));
    }

    public function searchHospital(Request $search){
      try {
        $data = $search->data;

        $hospitals = Hospital::where('name', 'like', '%' . $data . '%')
                             ->orWhere('city', 'like', '%' . $data . '%')->take(30)->get();
        return new ResponseResource($hospitals);
      } catch (\Exception $e) {
        return new ResponseResource($e);
      }
    }

    public function nearestHospitals(Request $request){
      try {
        $radiusOfEarthInKilometers = 6371;
        $latOfBoundingCircle = $request->input("lat");
        $longOfBoundingCircle = $request->input("lng");
        $radiusOfLocationsToSearchInKilometers = 10;

        // computations
        $minLat = $latOfBoundingCircle - $radiusOfLocationsToSearchInKilometers / $radiusOfEarthInKilometers * 180 / M_PI;
        $maxLat = $latOfBoundingCircle + $radiusOfLocationsToSearchInKilometers / $radiusOfEarthInKilometers * 180 / M_PI;
        $minLong = $longOfBoundingCircle - $radiusOfLocationsToSearchInKilometers / $radiusOfEarthInKilometers * 180 / M_PI / cos($latOfBoundingCircle * M_PI / 180);
        $maxLong = $longOfBoundingCircle + $radiusOfLocationsToSearchInKilometers/ $radiusOfEarthInKilometers * 180 / M_PI / cos($latOfBoundingCircle * M_PI / 180);

        $data = Hospital::whereBetween("lat", [$minLat, $maxLat])
                        ->whereBetween("lng", [$minLong, $maxLong])
                        ->take(30)->get();

        return new ResponseResource($data);
      } catch (\Exception $e) {
        return new ResponseResource($e);
      }



    }
}
