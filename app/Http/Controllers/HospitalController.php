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
}
