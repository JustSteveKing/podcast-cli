<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('podcasts', static function (Blueprint $table): void {
            $table->ulid('ulid')->primary();

            $table->string('title');
            $table->string('generator')->nullable();
            $table->string('copyright')->nullable();
            $table->string('language')->nullable();
            $table->string('link')->nullable();
            $table->text('description')->nullable();

            $table->json('image')->nullable();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
