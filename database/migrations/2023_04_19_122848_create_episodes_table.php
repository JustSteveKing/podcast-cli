<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episodes', static function (Blueprint $table): void {
            $table->ulid('id')->primary();

            $table->string('title');
            $table->string('link');
            $table->text('description');

            $table->json('media')->nullable();

            $table->boolean('listened')->default(false);

            $table
                ->foreignUlid('podcast_id')
                ->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
