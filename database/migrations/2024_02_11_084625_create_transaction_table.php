<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained('users')->noActionOnDelete();
            $table->foreignId('sales_id')->nullable()->constrained('users')->noActionOnDelete();

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
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
