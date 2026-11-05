<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('headline', 160)->nullable();

            $table->string('education', 180)->nullable();

            $table->text('bio')->nullable();

            $table->json('specialties')->nullable();

            $table->string('photo_path')->nullable();

            $table->boolean('is_public_profile')
                ->default(false)
                ->index();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropIndex(['is_public_profile']);

            $table->dropColumn([
                'headline',
                'education',
                'bio',
                'specialties',
                'photo_path',
                'is_public_profile',
            ]);

        });
    }
};