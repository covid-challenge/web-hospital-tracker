<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hospital;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ResponseResource;

class HospitalResourceController extends Controller
{

    private $dataTransformer;

    public function __construct() {
        $this->dataTransformer = function($item, $key) {
            $finalData = [];
            $ppe = [];
            $address = [];
    
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
            return $finalData;
        };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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

            $subquery = DB::table('hospital')
                            ->select(DB::raw("MAX(updateddate) as updated_date, cfname"))
                            ->groupBy('cfname');

            $data = Hospital::from("hospital as real_hospitals")
                            ->joinSub($subquery, "grouped_hospitals", function($join) {
                                $join->on("real_hospitals.cfname", "=", "grouped_hospitals.cfname")
                                    ->on("real_hospitals.updateddate", "=", "grouped_hospitals.updated_date");
                            })
                            ->whereNotNull("lat")
                            ->whereNotNull("lng")
                            ->whereBetween("lat", [$minLat, $maxLat])
                            ->whereBetween("lng", [$minLong, $maxLong])
                            ->paginate(40);

            $data->transform($this->dataTransformer);

            return new ResponseResource($data);
        } catch (\Exception $e) {
            return new ResponseResource($e);
        }
    }

    public function search(Request $request) {
        try {

            $subquery = DB::table('hospital')
                        ->select(DB::raw("MAX(updateddate) as updated_date, cfname"))
                        ->groupBy('cfname');

            $hospitalBeingSearched = Hospital::from("hospital as real_hospitals")
                            ->joinSub($subquery, "grouped_hospitals", function($join) {
                                $join->on("real_hospitals.cfname", "=", "grouped_hospitals.cfname")
                                    ->on("real_hospitals.updateddate", "=", "grouped_hospitals.updated_date");
                            })
                            ->whereNotNull("lat")
                            ->whereNotNull("lng")
                            ->where("real_hospitals.cfname", "like", $request->input("q") . "%")
                            ->paginate(40);

            $hospitalBeingSearched->transform($this->dataTransformer);

            return new ResponseResource($hospitalBeingSearched);
        } catch (\Exception $e) {
            return new ResponseResource($e);
        }
    }
}
