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
     private $dataTransformer;

     public function __construct() {
         $this->dataTransformer = function($item, $key) {
             $finalData = [];
             $ppe = [];
             $address = [];
             $infectedBreakdown = [];

             $totalInfected = 0;

             $parsedJson = json_decode($item);

             foreach($parsedJson as $itemKey => $value) {
                 switch($itemKey) {
                     // ppe
                     case "gown":
                     case "gloves":
                     case "head_cover":
                     case "goggles":
                     case "coverall":
                     case "shoe_cover":
                     case "face_shield":
                     case "surgmask":
                     case "n95mask":
                         $ppe[$itemKey] = $value;
                         break;

                     case "city_mun":
                         $address["city"] = $value;
                         break;

                     case "province":
                     case "region":
                         $address[$itemKey] = $value;
                         break;

                     case "icu_o":
                         $infectedBreakdown["icu"] = $value;
                         $totalInfected += $value;
                         break;
                     case "isolbed_o":
                         $infectedBreakdown["isolation"] = $value;
                         $totalInfected += $value;
                         break;
                     case "beds_ward_o":
                         $infectedBreakdown["ward"] = $value;
                         $totalInfected += $value;
                         break;

                     case "cfname":
                         $finalData["name"] = $value;
                         break;

                     default:
                         $finalData[$itemKey] = $value;
                         break;
                 }
             }

             $finalData["ppe"] = $ppe;
             $finalData["address"] = $address;
             $finalData["infected"] = [
                 "total" => $totalInfected,
                 "breakdown" => $infectedBreakdown
             ];
             return $finalData;
         };
     }

    public function index()
    {
      $subquery = DB::table('hospital')
      ->select(DB::raw("MAX(weekly_report_updated_date) as weekly_report_updated_date, cfname"))
      ->groupBy('cfname');

      $hospitals = Hospital::from("hospital as real_hospitals")
      ->joinSub($subquery, "grouped_hospitals", function($join) {
          $join->on("real_hospitals.cfname", "=", "grouped_hospitals.cfname")
              ->on("real_hospitals.weekly_report_updated_date", "=", "grouped_hospitals.weekly_report_updated_date");
      })
      ->whereNotNull('lat')
      ->whereNotNull('lng')
      ->get();

      return view('index', compact('hospitals'));
    }

    public function searchHospital(Request $search){
      $data = $search->data;
      $latOfBoundingCircle = $search->lat;
      $longOfBoundingCircle = $search->lng;
      try {

            $hospitalBeingSearched =  Hospital::select(DB::raw("
                    *, SQRT(
                        POW(69.1 * (lat - $latOfBoundingCircle), 2) +
                        POW(69.1 * ($longOfBoundingCircle - lng) * COS(lat / 57.3), 2)) AS distance"))
            ->where("cfname", "like", "%" . $data . "%")
            ->orWhere("city_mun", "like", "%" . $data . "%")
            ->orderBy('distance')
            ->whereNotNull("lat")
            ->whereNotNull("lng")
            ->take(40)->get();

            $hospitalBeingSearched->transform($this->dataTransformer);

            return new ResponseResource($hospitalBeingSearched);
        } catch (\Exception $e) {
            return new ResponseResource($e);
        }
    }

    public function nearestHospitals(Request $request){
      try {
          $latOfBoundingCircle = $request->input("lat");
          $longOfBoundingCircle = $request->input("lng");

          $data =  Hospital::select(DB::raw("
                    *, SQRT(
                        POW(69.1 * (lat - $latOfBoundingCircle), 2) +
                        POW(69.1 * ($longOfBoundingCircle - lng) * COS(lat / 57.3), 2)) AS distance"))
            ->havingRaw('distance < 10')
            ->orderBy('distance')
            ->whereNotNull("lat")
            ->whereNotNull("lng")
            ->take(40)->get();

          $data->transform($this->dataTransformer);

          return new ResponseResource($data);
      } catch (\Exception $e) {
          return new ResponseResource($e);
      }
    }
}
