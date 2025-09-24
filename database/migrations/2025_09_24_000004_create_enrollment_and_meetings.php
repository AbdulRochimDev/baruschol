<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->timestamps();

            $table->unique(['student_id','class_id','term_id'], 'enrollment_unique');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->nullOnDelete();
        });

        Schema::create('class_meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_subject_id');
            $table->string('day')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();

            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_meetings');
        Schema::dropIfExists('enrollments');
    }
};
