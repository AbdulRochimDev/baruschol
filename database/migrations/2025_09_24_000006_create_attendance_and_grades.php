<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_subject_id');
            $table->enum('status', ['draft','open','closed'])->default('draft');
            $table->date('date')->nullable();
            $table->timestamps();

            $table->unique(['class_subject_id','date'], 'attendance_session_unique');
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_session_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('status', ['present','absent','sick','excused'])->default('present');
            $table->timestamps();

            $table->unique(['attendance_session_id','student_id'], 'attendance_record_unique');
            $table->foreign('attendance_session_id')->references('id')->on('attendance_sessions')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::create('grade_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_subject_id');
            $table->string('name');
            $table->decimal('weight', 5, 2)->default(0);
            $table->timestamps();

            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grade_item_id');
            $table->unsignedBigInteger('student_id');
            $table->decimal('score', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['grade_item_id','student_id'], 'grade_unique');
            $table->foreign('grade_item_id')->references('id')->on('grade_items')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('grade_items');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('attendance_sessions');
    }
};
