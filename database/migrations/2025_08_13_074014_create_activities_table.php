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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_id')
                ->constrained('schools')
                ->onDelete('cascade');

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_photo')->nullable();
            $table->text('description')->nullable();

            // Nouveaux champs
            $table->date('start_date')->nullable();       // Date de début de l'activité
            $table->date('end_date')->nullable();         // Date de fin de l'activité
            $table->string('location')->nullable();       // Lieu de l'activité
            $table->string('organizer')->nullable();      // Organisateur / responsable
            $table->string('type')->nullable();           // Type : conférence, workshop, sortie...
            $table->string('audience')->nullable();       // Public cible : étudiants, professeurs...
            $table->string('language')->default('fr');    // Langue utilisée

            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
