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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecturer_id');
            $table->foreign('lecturer_id')->references('id')->on('users');
            $table->string('section'); // 'thesis' or 'forms'
            $table->string('file_name');
            $table->text('description')->nullable(); // Description for students
            // $table->binary('file_data'); //store the file in pdf,image,or docs
            $table->string('file_data'); //store the file in pdf,image,or docs
            $table->string('mime_type');
            $table->string('semester');//foreign key from semester table
            $table->string('status');// 'hidden' or 'active'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template');
    }
};
