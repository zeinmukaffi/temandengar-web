<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ai_assistance_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_id')
                ->constrained('consultation_requests')
                ->cascadeOnDelete();

            $table->foreignId('counselor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('task_type', 50);

            $table->string('model_name')->nullable();

            $table->text('generated_content');

            $table->dateTime('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_assistance_logs');
    }
};