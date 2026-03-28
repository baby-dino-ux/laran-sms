<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id('scholarship_id');
            $table->string('scholarship_name');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->integer('slots')->default(0);
            $table->date('deadline')->nullable();
            $table->enum('status', ['active', 'inactive', 'closed'])->default('active');
            $table->json('eligibility_criteria')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')
                  ->references('user_id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
