<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hospital;

class HospitalResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $radiusOfEarthInKilometers = 6371;
        $latOfBoundingCircle = 14.5680867;
        $longOfBoundingCircle = 120.8805591;
        $radiusOfManilaInKilometers = 200;
        

        // computations
        $minLat = $latOfBoundingCircle - $radiusOfManilaInKilometers / $radiusOfEarthInKilometers * 180 / M_PI;
        $maxLat = $latOfBoundingCircle + $radiusOfManilaInKilometers / $radiusOfEarthInKilometers * 180 / M_PI;
        $minLong = $longOfBoundingCircle - $radiusOfManilaInKilometers / $radiusOfEarthInKilometers * 180 / M_PI / cos($latOfBoundingCircle * M_PI / 180);
        $maxLong = $longOfBoundingCircle + $radiusOfManilaInKilometers/ $radiusOfEarthInKilometers * 180 / M_PI / cos($latOfBoundingCircle * M_PI / 180);

        // $data = Hospital::whereBetween("lat", [$minLat, $maxLat])
        //                 ->whereBetween("lng", [$minLong, $maxLong])
        //                 ->get();

        $data = Hospital::all();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
