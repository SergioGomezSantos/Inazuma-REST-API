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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->integer("GP");
            $table->integer("TP");
            $table->integer("kick");
            $table->integer("body");
            $table->integer("control");
            $table->integer("guard");
            $table->integer("speed");
            $table->integer("stamina");
            $table->integer("guts");
            $table->integer("freedom");
            $table->string("version");
            $table->foreignId('player_id')->constrained('players')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
