<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('transactions', function (Blueprint $table) {
            $table->String('hash');
            $table->integer('buyer_id');
            $table->integer('seller_id');
            $table->String('beat_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('hash');
            $table->dropColumn('buyer_id');
            $table->dropColumn('seller_id');
            $table->dropColumn('beat_id');
        });
    }
}
