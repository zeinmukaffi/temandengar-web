<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schedule_reminder_deliveries', function (Blueprint $table) {

            $table->id();

            $table->foreignId('consultation_id')
                ->constrained('consultations')
                ->cascadeOnDelete();

            // Snapshot jadwal ketika reminder dibuat
            $table->dateTime('scheduled_at');

            // 24h atau 1h
            $table->string('reminder_type', 10);

            // student atau counselor
            $table->string('recipient_type', 20);

            $table->string('recipient_email');
            $table->string('recipient_name');

            // pending atau sent
            $table->string('status', 20)->default('pending');

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            // Mencegah reminder ganda untuk jadwal dan penerima yang sama
            $table->unique(
                [
                    'consultation_id',
                    'scheduled_at',
                    'reminder_type',
                    'recipient_type',
                ],
                'schedule_reminder_delivery_unique'
            );

            $table->index(['status', 'reminder_type']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_reminder_deliveries');
    }
};
