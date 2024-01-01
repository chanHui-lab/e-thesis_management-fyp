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
        Schema::create('proposal_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('proposal_title');
            $table->text('proposal_description');

            //store data and uplaoded date
            $table->json('proposal_file');
            $table->string('proposal_type');  //machine leanring, web dev, mobile app, blah blah

            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->unsignedBigInteger('submission_post_id');
            $table->unsignedBigInteger('sem_id');

            $table->timestamps();

            $table->foreign('student_id')->references('stu_id')->on('students')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('lecturer_id')->on('supervisors')->onDelete('cascade');
            $table->foreign('submission_post_id')->references('id')->on('submission_posts')->onDelete('cascade');
            $table->foreign('sem_id')->references('id')->on('semester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_submissions');
    }
};
