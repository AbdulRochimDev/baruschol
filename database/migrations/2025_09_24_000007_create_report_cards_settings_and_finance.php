<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('term_id');
            $table->decimal('final_score', 8, 2)->nullable();
            $table->timestamps();

            $table->unique(['student_id','term_id'],'report_card_unique');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('cascade');
        });

        Schema::create('report_card_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_card_id');
            $table->unsignedBigInteger('class_subject_id');
            $table->decimal('score', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('report_card_id')->references('id')->on('report_cards')->onDelete('cascade');
            $table->foreign('class_subject_id')->references('id')->on('class_subjects')->onDelete('cascade');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('code')->unique();
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });

        Schema::create('payment_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id')->unique();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ledger_id');
            $table->string('posting_id')->unique(); // idempotent posting reference
            $table->decimal('amount', 14, 2);
            $table->string('type'); // debit/credit
            $table->timestamps();

            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledger_entries');
        Schema::dropIfExists('ledgers');
        Schema::dropIfExists('payment_verifications');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('report_card_items');
        Schema::dropIfExists('report_cards');
    }
};
