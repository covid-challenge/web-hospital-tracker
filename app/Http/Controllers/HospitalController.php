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
      ->select(DB::raw("MAX(updateddate) as updated_date, cfname"))
      ->groupBy('cfname');

      $hospitals = Hospital::from("hospital as real_hospitals")
      ->joinSub($subquery, "grouped_hospitals", function($join) {
          $join->on("real_hospitals.cfname", "=", "grouped_hospitals.cfname")
              ->on("real_hospitals.updateddate", "=", "grouped_hospitals.updated_date");
      })
      ->whereNotNull('lat')
      ->whereNotNull('long')
      ->get();
  
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
}
