<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_profile', function (Blueprint $table) {
            $table->string('professional_group')->nullable();
            $table->dropColumn('institute');
            $table->dropColumn('qualification');
            $table->dropColumn('field_of_study');
            $table->dropColumn('year_of_graduation');
            $table->dropColumn('position_rank');
            $table->dropColumn('company');
            $table->dropColumn('location');
            $table->dropColumn('work_role');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_profile', function (Blueprint $table) {
            $table->dropColumn('professional_group');
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
        });
    }
}
