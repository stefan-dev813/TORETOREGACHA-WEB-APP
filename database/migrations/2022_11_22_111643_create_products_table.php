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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('point')->default(0);
            $table->integer('dp')->nullable();
            $table->string('rare')->nullable();
            // $table->decimal('emission_percentage', 8, 4)->default(0.0);
            $table->string('image');

            $table->integer('marks')->default(0); // For Lost products

            $table->tinyInteger('is_last')->default(0); // if it is last, then 1
            $table->string('lost_type')->nullable();
            $table->tinyInteger('is_lost_product')->default(0); // if it is lost product, then 1   if it is dp product, then 2
            $table->bigInteger('gacha_id')->default(0);

            $table->integer('category_id')->default(0);
            $table->string('status_product')->nullable();
            $table->string('product_type')->nullable();   //for dp product

            $table->integer('gacha_record_id')->default(0);
            $table->integer('user_id')->default(0);

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
        Schema::dropIfExists('products');
    }
};
