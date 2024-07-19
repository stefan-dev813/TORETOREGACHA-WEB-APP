<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->integer('gacha_limit_status')->default(0);
            $table->integer('gacha_limit_on_setting')->default(0);
            $table->string('starting_day')->default('0000-00-00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gachas', function (Blueprint $table) {
            $table->dropColumn('gacha_limit_status');
            $table->dropColumn('gacha_limit_on_setting');
            $table->dropColumn('starting_day');
        });
    }
};
