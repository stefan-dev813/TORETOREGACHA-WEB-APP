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
        Schema::create('gachas', function (Blueprint $table) {
            $table->id();
            $table->integer('point'); // 送信先
            $table->integer('count_card'); // all
            $table->integer('count')->default(0); // current count
            $table->string('lost_product_type')->nullable();
            $table->string('thumbnail');
            $table->string('image');
            $table->integer('category_id'); 
            $table->integer('order_level')->default(100000); 
            $table->tinyInteger('status')->default(0);  
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
        Schema::dropIfExists('gachas');
    }
};
