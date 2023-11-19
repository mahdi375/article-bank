<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('hash');
            $table->string('title')->fullText();
            $table->mediumText('description')->nullable();
            $table->text('content')->nullable();
            $table->mediumText('url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('source_id')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();

            $table->index('hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
