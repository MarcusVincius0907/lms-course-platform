<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderItem', function (Blueprint $table) {

            $table->id();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->bigInteger('order_id')->unsigned(); 

            $table->foreign('course_id')->references('id')->on('courses');
            $table->bigInteger('course_id')->unsigned(); 

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
        Schema::dropIfExists('orderItem');
    }
}
