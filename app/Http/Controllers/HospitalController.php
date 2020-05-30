<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hospital;
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
}
