<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificate_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('reason')->nullable(); // Razão da solicitação
            $table->text('feedback')->nullable(); // Feedback do professor
            $table->integer('total_hours')->default(0); // Total de horas aprovadas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_requests');
    }
}; 