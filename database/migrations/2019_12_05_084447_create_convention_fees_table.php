<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConventionFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convention_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('convention_id');
            $table->string('name')->nullable();
            $table->string('price')->nullable();
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
        Schema::dropIfExists('convention_fees');
    }
}
