<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feedback_reminder_deliveries', function (Blueprint $table) {

            $table->id();

            // Satu reminder maksimal untuk satu konsultasi
            $table->foreignId('consultation_id')
                ->unique()
                ->constrained('consultations')
                ->cascadeOnDelete();

            // Snapshot penerima email
            $table->string('recipient_email');
            $table->string('recipient_name', 100)->nullable();

            /*
             * awaiting_invitation:
             * Menunggu email undangan berhasil dikirim.
             *
             * pending:
             * Undangan berhasil, menunggu 24 jam.
             *
             * sent:
             * Reminder berhasil dikirim.
             *
             * skipped:
             * Feedback sudah diisi sebelum reminder.
             */
            $table->string('status', 30)
                ->default('awaiting_invitation')
                ->index();

            $table->timestamp('invitation_sent_at')->nullable();

            $table->timestamp('due_at')->nullable()->index();

            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_reminder_deliveries');
    }
};
