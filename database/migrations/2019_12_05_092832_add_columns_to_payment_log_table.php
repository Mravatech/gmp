<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPaymentLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_log', function (Blueprint $table) {
            $table->string('reference')->nullable();
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_log', function (Blueprint $table) {
            $table->dropColumn('reference');
            $table->dropColumn('status');
        });
    }
}
