<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsPagarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments_pagar', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('user_id')->unsigned(); //Which Instructor send request
            $table->string('amount');
            $table->string('transfer_id');
            $table->string('payment_method');
            $table->text('url')->nullable();
            $table->text('code')->nullable();
            $table->dateTime('expire_in')->nullable();
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
        Schema::dropIfExists('payments_pagar');
    }
}
