<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('request_id')
                ->unique()
                ->constrained('consultation_requests')
                ->cascadeOnDelete();

            $table->foreignId('counselor_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->dateTime('scheduled_at');

            $table->string('method', 20);

            $table->string('meeting_link')->nullable();
            $table->string('location')->nullable();

            $table->string('session_status', 30)
                ->default('scheduled');

            $table->text('follow_up_note')->nullable();

            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};