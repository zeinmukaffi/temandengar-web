<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultation_requests', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_number', 30)->unique();

            $table->string('alias', 100);
            $table->string('email', 150)->nullable();
            $table->string('phone', 20)->nullable();

            $table->string('topic', 60);
            $table->text('description');

            $table->string('preferred_method', 20)->default('online');
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time', 50)->nullable();

            $table->string('status', 30)
                ->default('pending')
                ->index();

            $table->timestamp('consent_at');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_requests');
    }
};