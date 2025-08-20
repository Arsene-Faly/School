<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->enum('type', ['public', 'prive'])
                ->default('public');

            $table->string('nif')->nullable();
            $table->string('stat')->nullable();
            $table->string('adresse')->nullable();
            $table->string('contact')->nullable();
            $table->string('document')->nullable();

            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');

            $table->text('admin_comment')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
