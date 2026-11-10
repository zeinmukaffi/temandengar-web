<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('consultation_feedback', function (Blueprint $table) {

            $table->id();
            $table->foreignId('consultation_id')
                ->unique()
                ->constrained('consultations')
                ->cascadeOnDelete();
            $table->string('display_name', 100)
                ->default('Mahasiswa');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->boolean('consent_to_publish')
                ->default(false);
            $table->boolean('is_published')
                ->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index([
                'is_published',
                'consent_to_publish'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_feedback');
    }
};