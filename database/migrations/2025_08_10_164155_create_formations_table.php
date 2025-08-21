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
        Schema::create('formations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_photo')->nullable();
            $table->text('description')->nullable();

            // Nouveaux champs
            $table->string('level')->nullable(); // Niveau : L1, L2, L3, Master...
            $table->integer('duration')->nullable(); // Durée en mois ou années
            $table->string('mode')->nullable(); // Mode : présentiel, en ligne, hybride
            $table->string('language')->default('fr'); // Langue d’enseignement
            $table->decimal('price', 10, 2)->nullable(); // Prix de la formation
            $table->string('category')->nullable(); // Catégorie : informatique, marketing...

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
