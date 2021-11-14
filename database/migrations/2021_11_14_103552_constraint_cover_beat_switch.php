<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConstraintCoverBeatSwitch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beats', function (Blueprint $table) {
            $table->string('cover_id');
        });
        Schema::table('covers', function (Blueprint $table) {
            $table->dropColumn('beat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beats', function (Blueprint $table) {
            //
        });
    }
}
