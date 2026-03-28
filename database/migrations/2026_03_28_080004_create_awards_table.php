<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('scholarship_id');
            $table->unsignedBigInteger('application_id')->nullable();
            $table->decimal('amount_granted', 10, 2);
            $table->date('award_date');
            $table->text('notes')->nullable();
            $table->boolean('notification_sent')->default(false);
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('scholarship_id')
                  ->references('scholarship_id')->on('scholarships')
                  ->onDelete('cascade');

            $table->foreign('application_id')
                  ->references('id')->on('applications')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};
