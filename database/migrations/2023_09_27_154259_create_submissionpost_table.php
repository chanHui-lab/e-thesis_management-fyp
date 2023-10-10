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
        Schema::create('submissionPost', function (Blueprint $table) {
            $table->id();

            $table->string('title');

            $table->text('description')->nullable(); // Description for students

            $table->unsignedBigInteger('lecturer_id');
            $table->foreign('lecturer_id')->references('id')->on('users');

            $table->json('files'); // This column will store JSON data with file information

            $table->string('section'); // 'thesis' or 'forms' or 'slides'

            $table->dateTime('submission_deadline')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissionPost');
    }
};
