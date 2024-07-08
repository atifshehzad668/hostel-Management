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
        Schema::create('recived_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->decimal('total_amount');
            $table->decimal('paid_amount');
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recived_payments');
    }
};