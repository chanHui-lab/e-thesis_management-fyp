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
        Schema::create('students', function (Blueprint $table) {
            // $table->id();

            $table->unsignedBigInteger('stu_id');
            $table->string('matric_number')->unique();

            $table->unsignedBigInteger('supervisor_id')->nullable();

            $table->foreign('stu_id')->references('id')->on('users');
            $table->foreign('supervisor_id')->references('lecturer_id')->on('supervisors')->onDelete('set null');

            $table->unsignedBigInteger('semester_id');
            $table->foreign('semester_id')->references('id')->on('semester');

            // $table->unsignedBigInteger('semester1_grade_id');
            // $table->foreign('semester1_grade_id')->references('gpa')->on('grades');

            // $table->unsignedBigInteger('semester2_grade_id');
            // $table->foreign('semester2_grade_id')->references('gpa')->on('grades');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
