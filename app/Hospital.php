<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $table = 'hospital';
    protected $primaryKey = 'id';
    protected $hidden = array("updated_date", "addeddate", "reportdate", "region_psgc");
}
