<?php

use App\Enums\StatutConge;
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
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->index()->constrained()->cascadeOnDelete();
            $table->string('type_conge')->index();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('statut')->default(StatutConge::EnAttente->value)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};
