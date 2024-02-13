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
        Schema::create('transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('transaction_user_id_foreign');
            $table->unsignedBigInteger('sales_id')->nullable()->index('transaction_sales_id_foreign');
            $table->string('code')->nullable();
            $table->date('booking_date')->nullable();
            $table->string('start_time', 59)->nullable();
            $table->string('end_time', 59)->nullable();
            $table->text('address')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('total_price')->nullable();
            $table->string('status')->default('PENDING');
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
        Schema::dropIfExists('transaction');
    }
};
