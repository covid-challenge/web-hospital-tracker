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

            $data = Hospital::whereNotNull("lat")
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

            $hospitalBeingSearched = Hospital::whereNotNull("lat")
                                            ->whereNotNull("lng")
                                            ->where("cfname", "like", $request->input("q") . "%")
                                            ->orWhere("city_mun", "like", $request->input("q") . "%")
                                            ->paginate(40);

            $hospitalBeingSearched->transform($this->dataTransformer);

            return new ResponseResource($hospitalBeingSearched);
        } catch (\Exception $e) {
            return new ResponseResource($e);
        }
    }
}
