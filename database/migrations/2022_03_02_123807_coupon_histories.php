<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CouponHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_histories', function (Blueprint $table) {
            $table->id();

            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('user_id')->unsigned(); 

            $table->foreign('course_id')->references('id')->on('courses');
            $table->bigInteger('course_id')->unsigned(); 

            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->bigInteger('coupon_id')->unsigned();

            $table->double('discount')->nullable();

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
        Schema::dropIfExists('coupon_histories');
    }
}
