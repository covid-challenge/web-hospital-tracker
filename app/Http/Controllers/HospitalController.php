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
      $subquery = DB::table('hospital')
      ->select(DB::raw("MAX(updated_date) as updated_date, cfname"))
      ->groupBy('cfname');

      $hospitals = Hospital::from("hospital as real_hospitals")
      ->joinSub($subquery, "grouped_hospitals", function($join) {
          $join->on("real_hospitals.cfname", "=", "grouped_hospitals.cfname")
              ->on("real_hospitals.updated_date", "=", "grouped_hospitals.updated_date");
      })
      ->whereNotNull('lat')
      ->whereNotNull('lng')
      ->get();

      return view('index', compact('hospitals'));
    }

    public function searchHospital(Request $search){
      try {
        $data = $search->data;

        $hospitals = Hospital::where('cfname', 'like', '%' . $data . '%')
                             ->orWhere('city_mun', 'like', '%' . $data . '%')->take(30)->get();
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
