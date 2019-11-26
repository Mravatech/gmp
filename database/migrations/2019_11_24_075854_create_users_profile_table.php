<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_profile', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->unsignedBigInteger('user_id');
            $table->string('member_id');
            $table->string('grade')->nullable();
            $table->string('title')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('phone_number')->nullable();
            $table->longText('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('chapter')->nullable();
            $table->string('sector')->nullable();
            $table->string('sub_sector')->nullable();
            $table->string('occupation')->nullable();
            $table->string('institute')->nullable();
            $table->string('qualification')->nullable();
            $table->string('field_of_study')->nullable();
            $table->string('year_of_graduation')->nullable();
            $table->string('position_rank')->nullable();
            $table->string('company')->nullable();
            $table->string('location')->nullable();
            $table->string('work_role')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->longText('description')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('users_profile');
    }
}
