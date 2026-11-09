<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->boolean('is_accepting_consultations')
                ->default(true);

        });

        Schema::table('consultation_requests', function (Blueprint $table) {

            $table->foreignId('preferred_counselor_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('consultation_requests', function (Blueprint $table) {

            $table->dropConstrainedForeignId('preferred_counselor_id');

        });

        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn('is_accepting_consultations');

        });
    }
};