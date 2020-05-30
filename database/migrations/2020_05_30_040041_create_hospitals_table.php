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
        Schema::create('hospital', function (Blueprint $table) {
            $table->id();
            $table->string('cfname')->nullable();
            $table->string('updateddate')->nullable();
            $table->string('addeddate')->nullable();
            $table->string('reportdate')->nullable();
            $table->integer('gown')->default(0);
            $table->integer('gloves')->default(0);
            $table->integer('head_cover')->default(0);
            $table->integer('goggles')->default(0);
            $table->integer('coverall')->default(0);
            $table->integer('shoe_cover')->default(0);
            $table->integer('face_shield')->default(0);
            $table->integer('surgmask')->default(0);
            $table->integer('n95mask')->default(0);
            $table->string('region')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('lng')->nullable();

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
