<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->string('operator_type');
            $table->string('amenity')->default('Unspecified');
            $table->string('status')->default('Unspecified');
            $table->integer('accommodate_emergencies')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hospitals');
    }
}
