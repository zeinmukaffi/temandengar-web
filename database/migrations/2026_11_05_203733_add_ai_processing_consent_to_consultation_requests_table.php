<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('consultation_requests', function (Blueprint $table) {

            $table->boolean('ai_processing_consent')
                ->default(false);

            $table->timestamp('ai_processing_consented_at')
                ->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('consultation_requests', function (Blueprint $table) {

            $table->dropColumn([
                'ai_processing_consent',
                'ai_processing_consented_at',
            ]);

        });
    }
};