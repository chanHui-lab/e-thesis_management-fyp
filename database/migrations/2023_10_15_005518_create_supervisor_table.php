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
        Schema::create('supervisors', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('lecturer_id')->unique(); // Foreign key to users table
            $table->foreign('lecturer_id')->references('id')->on('users');
            $table->string('department_name'); // Foreign key to users table

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisors');
    }
};
